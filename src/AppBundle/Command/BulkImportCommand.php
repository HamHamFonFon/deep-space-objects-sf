<?php

namespace AppBundle\Command;

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
        'Ap' => '',
        'He' => '',
        'Ba' => '',
        'Be' => '',
        'Bi' => '',
        'Bo' => '',
        'B1' => '',
        'B2' => '',
        'B3' => '',
        'B8' => '',
        'B9' => '',
        'K1' => '',
        'K2' => '',
        'K3' => '',
        'M1' => '',
        'M2' => '',
        'M3' => '',
        'M4' => '',
        'AP' => '',
        'Cz' => '',
        'Ki' => '',
        'Do' => '',
        'Pa' => '',
        'Pe' => '',
        'Ce' => '',
        'Ru' => '',
        'Ly' => '',
        'Ha' => '',
        'Ho' => '',
        'Hu' => '',
        'vd' => '',
        'Ca' => '',
        'La' => '',
        'Me' => '',
        '3C' => '',
        'To' => '',
        'Tr' => '',
        'Gu' => '',
        'Gr' => '',
        'Pi' => '',
        'Fe' => '',
        'Ro' => ''

    ];

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
        $this->transformId();
        $this->transformCatalog();
    }

    private function transformId()
    {
        $dataFile =  $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.json';
        $contentFile = file_get_contents($dataFile);

        // Change ID
        $newData = preg_replace_callback('/%randId%/', function() {
            $datetime = new \DateTime();
            $prefix = $datetime->getTimestamp();
            return md5(uniqid($prefix));
        }, $contentFile);

        $dataNewFile =  $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.generate.json';
        file_put_contents($dataNewFile, $newData);

        return;
    }

    /**
     *
     */
    private function transformCatalog()
    {
        $file = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.generate.json';
        $jsonFile = json_decode(file_get_contents($file), true);

        $mapping = self::$mapping;
        foreach ($jsonFile['bulkData'] as $dso) {
            if (preg_match('/%catalog%/', json_encode($dso))) {
                $type = substr($dso['id'], 0, 2);
                $valueReplace = $mapping[$type];
                $dso = preg_replace_callback('/%catalog%/', function() use ($valueReplace){
                    return $valueReplace;
                }, json_encode($dso));
            }
        }

        dump($jsonFile['bulkData']);
        return;
    }
}
