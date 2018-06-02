<?php

namespace AppBundle\Command;

use AppBundle\Repository\DsoRepository;
use Kuzzle\Kuzzle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BulkImportCommand extends ContainerAwareCommand
{
    protected $defaultCatalog = 'unassigned';

    protected static $mapping = [
        'NG' => 'ngc',
        'IC' => 'ic',
        'LD' => 'ldn',
        'Sh' => 'sh',
        'Cr' => 'cr',
        'St' => 'sto',
        'Ab' => 'abl',
        'UG' => 'ugc',
        'An' => 'unassigned', 'Ap' => 'unassigned', 'AP' => 'unassigned',
        'He' => 'unassigned',
        'Ba' => 'unassigned', 'Be' => 'unassigned', 'Bi' => 'unassigned', 'Bo' => 'unassigned',
        'B1' => 'unassigned', 'B2' => 'unassigned', 'B3' => 'unassigned', 'B4' => 'unassigned', 'B5' => 'unassigned', 'B6' => 'unassigned', 'B7' => 'unassigned', 'B8' => 'unassigned', 'B9' => 'unassigned',
        'K1' => 'unassigned', 'K2' => 'unassigned', 'K3' => 'unassigned', 'K4' => 'unassigned',
        'M1' => 'unassigned', 'M2' => 'unassigned', 'M3' => 'unassigned', 'M4' => 'unassigned', 'M7' => 'unassigned',
        'Cz' => 'unassigned',
        'Ki' => 'unassigned',
        'Do' => 'unassigned',
        'Pa' => 'unassigned', 'Pe' => 'unassigned',
        'Ce' => 'unassigned',
        'Ru' => 'unassigned',
        'Ly' => 'unassigned',
        'Ha' => 'unassigned', 'Ho' => 'unassigned', 'Hu' => 'unassigned',
        'H1' => 'unassigned', 'H2' => 'unassigned',
        'vd' => 'unassigned',
        'Ca' => 'unassigned',
        'La' => 'unassigned',
        'Me' => 'unassigned',
        '3C' => 'unassigned',
        'Te' => 'unassigned', 'To' => 'unassigned', 'Tr' => 'unassigned',
        'Gu' => 'unassigned', 'Gr' => 'unassigned',
        'Pi' => 'unassigned',
        'Fe' => 'unassigned',
        'Ro' => 'unassigned',
        'Jo' => 'unassigned',
        'J3' => 'unassigned', 'J9' => 'unassigned',
        'Vd' => 'unassigned', 'VV' => 'unassigned', 'vy' => 'unassigned', 'VY' => 'unassigned'
    ];

    private $kernel;
    private $srcFile = 'Resources/data/Bulks/dso20.bulk.src.json';
    private $generatedFile = 'Resources/data/Bulks/dso20.bulk.generate.json';
    protected $kuzzle;
    private $kuzzleIndex;
    protected $listErrors = [];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('dso:import:bulk')->setDescription('Import DSO data from bulk and insert into collection');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Files
        $this->kernel = $kernel = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR;
        $this->srcFile = $kernel . $this->srcFile;
        $this->generatedFile = $kernel . $this->generatedFile;

        // Init Kuzzle
        $kuzzleHost = $this->getContainer()->getParameter('kuzzle_host');
        $this->kuzzleIndex = $this->getContainer()->getParameter('kuzzle_index');
        $kuzzlePort = $this->getContainer()->getParameter('kuzzle_port');

        /** @var Kuzzle kuzzle */
        $this->kuzzle = new Kuzzle($kuzzleHost, [
            'defaultIndex' => $this->kuzzleIndex,
            'port' => $kuzzlePort
        ]);

        $this->transformCatalog($output);

        if (0 < count($this->listErrors)) {
            $output->writeln($this->listErrors);
        }
    }


    /**
     * @deprecated
     */
    private function transformId()
    {
        $contentFile = file_get_contents($this->srcFile);

        // Change ID
        $newData = preg_replace_callback('/%randId%/', function() {
            return $this->generateKuzzleId();
        }, $contentFile);

        file_put_contents($this->generatedFile, $newData);

        return;
    }

    /**
     * @param OutputInterface $output
     */
    private function transformCatalog(OutputInterface $output)
    {
        $mappingCatalog = self::$mapping;
        if (!file_exists($this->srcFile) || !is_readable($this->srcFile)) {
            dump('Error reading source file');
        }
        $jsonData = json_decode(file_get_contents($this->srcFile), true);
        $progressBar = new ProgressBar($output, count($jsonData['bulkData'])/2);
        $progressBar->start();
        foreach ($jsonData['bulkData'] as $key=>$dso) {
            $document = null;
            $id = $this->generateKuzzleId();
            if (preg_match('/%catalog%/', json_encode($dso))) {
                $type = substr($dso['id'], 0, 2);
                $valueReplace = $mappingCatalog[$type];
                $dso['id'] = strtolower($dso['id']); // REMOVE when apply normalizer on collection

                $dsoStr = json_encode($dso);
                $newValueCatalog = preg_replace_callback('/%catalog%/', function() use ($valueReplace){
                    return $valueReplace;
                }, $dsoStr);

                $document = json_decode($newValueCatalog, true);

            } elseif (array_key_exists('catalog', $dso) && '%catalog%' !== $dso['catalog']) {
                $document = $dso;
            }

            if (preg_match('/%order%/', json_encode($document))) {
                $order = filter_var($dso['data']['desig'], FILTER_SANITIZE_NUMBER_INT);
                if (!is_numeric($order)) {
                    $order = 0;
                }

                $dsoStr = json_encode($document);
                $newOrderCatalog = preg_replace_callback('/"%order%"/', function() use ($order){
                    return $order;
                }, $dsoStr);

                $document = json_decode($newOrderCatalog, true);
            }

            if (isset($document)) {
                $this->insertDso($id, $document);
                $progressBar->advance();
            }
        }
        $progressBar->finish();
        $output->writeln("\n");

        return;
    }


    /**
     * Insert data in Kuzzle
     * @param $id
     * @param $document
     */
    private function insertDso($id, $document)
    {
        $collectionName = DsoRepository::COLLECTION_NAME;
        $kuzzleCollection = $this->kuzzle->collection($collectionName, $this->kuzzleIndex);
        try {
            $kuzzleCollection->createDocument($document, $id);
        } catch(\ErrorException $e) {
            $this->listErrors[] = '[' . serialize($document) . ']' . $e->getMessage();
        } catch (\Exception $e) {
            $this->listErrors[] = '[' . serialize($document) . ']' . $e->getMessage();
        }
        return;
    }

    /**
     * Generate unique ID
     * @return string
     */
    private function generateKuzzleId()
    {
        $datetime = new \DateTime();
        $prefix = $datetime->getTimestamp();
        return md5(uniqid($prefix));
    }
}
