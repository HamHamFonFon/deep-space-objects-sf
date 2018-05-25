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
        $mapping = self::$mapping;
        $file = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.generate.json';
        $jsonData = json_decode(file_get_contents($file), true);

        foreach ($jsonData['bulkData'] as $key=>$dso) {
            if (preg_match('/%catalog%/', json_encode($dso))) {
                $type = substr($dso['id'], 0, 2);
                $valueReplace = $mapping[$type];

                $dsoStr = json_encode($dso);

                $newValueCatalog = preg_replace_callback('/%catalog%/', function() use ($valueReplace){
                    return $valueReplace;
                }, $dsoStr);

                $jsonData['bulkData'][$key] = json_decode($newValueCatalog);
//                dump($newValueCatalog) . "\n";
            }
        }

        $dataCleanFile =  $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.generate.json';
        file_put_contents($dataCleanFile, json_encode($jsonData, JSON_UNESCAPED_SLASHES));
        return;
    }
}
