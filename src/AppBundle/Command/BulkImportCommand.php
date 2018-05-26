<?php

namespace AppBundle\Command;

use AppBundle\Repository\DsoRepository;
use Kuzzle\Kuzzle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BulkImportCommand extends ContainerAwareCommand
{
    protected static $mapping = [
        'NG' => 'ngc',
        'IC' => 'ic',
        'LD' => 'ldn',
        'Sh' => 'sh',
        'Cr' => 'cr',
        'St' => 'sto',
        'Ab' => 'abl',
        'UG' => 'ugc',
        'An' => '', 'Ap' => '', 'AP' => '',
        'He' => '',
        'Ba' => '', 'Be' => '', 'Bi' => '', 'Bo' => '',
        'B1' => '', 'B2' => '', 'B3' => '', 'B4' => '', 'B5' => '', 'B6' => '', 'B7' => '', 'B8' => '', 'B9' => '',
        'K1' => '', 'K2' => '', 'K3' => '', 'K4' => '',
        'M1' => '', 'M2' => '', 'M3' => '', 'M4' => '', 'M7' => '',
        'Cz' => '',
        'Ki' => '',
        'Do' => '',
        'Pa' => '', 'Pe' => '',
        'Ce' => '',
        'Ru' => '',
        'Ly' => '',
        'Ha' => '', 'Ho' => '', 'Hu' => '',
        'H1' => '', 'H2' => '',
        'vd' => '',
        'Ca' => '',
        'La' => '',
        'Me' => '',
        '3C' => '',
        'Te' => '', 'To' => '', 'Tr' => '',
        'Gu' => '', 'Gr' => '',
        'Pi' => '',
        'Fe' => '',
        'Ro' => '',
        'Jo' => '',
        'J3' => '', 'J9' => '',
        'Vd' => '', 'VV' => ''
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
        $this->setName('dso:import:bulk')->setDescription('Hello PhpStorm');
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

//        $this->transformId();
        $this->transformCatalog();
    }

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
     *
     */
    private function transformCatalog()
    {
        $mappingCatalog = self::$mapping;
        $jsonData = json_decode(file_get_contents($this->srcFile), true);

        foreach ($jsonData['bulkData'] as $key=>$dso) {
            $document = null;
            $id = $this->generateKuzzleId();
            if (preg_match('/%catalog%/', json_encode($dso))) {

                $type = substr($dso['id'], 0, 2);
                $valueReplace = $mappingCatalog[$type];

                $dsoStr = json_encode($dso);
                $newValueCatalog = preg_replace_callback('/%catalog%/', function() use ($valueReplace){
                    return $valueReplace;
                }, $dsoStr);

                $document = json_decode($newValueCatalog, true);

            }elseif (array_key_exists('catalog', $dso) && '%catalog%' !== $dso['catalog']) {
                $document = $dso;
            }

            if (isset($document)) {
                $this->insertDso($id, $document);
            }
        }
        return;
    }


    /**
     * Insert data in Kuzzle
     * @param $id
     * @param $document
     */
    private function insertDso($id, $document)
    {
        $kuzzleCollection = $this->kuzzle->collection(DsoRepository::COLLECTION_NAME, $this->kuzzleIndex);
        try {
            $kuzzleCollection->createDocument($document, $id);
        } catch(\ErrorException $e) {
            $this->listErrors[] = '[' . $document['id'] . ']' . $e->getMessage();
        } catch (\Exception $e) {
            $this->listErrors[] = '[' . $document['id'] . ']' . $e->getMessage();
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
