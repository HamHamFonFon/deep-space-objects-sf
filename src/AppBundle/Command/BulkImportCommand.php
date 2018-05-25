<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BulkImportCommand extends ContainerAwareCommand
{
    protected static $mapping = [
        'NGC' => 'ngc',
        'IC' => 'ic',
        'LDN' => 'ldn',
        'Sh2' => 'sh',
        'Cr' => 'cr',
        'Sto' => 'sto'
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


    private function transformCatalog()
    {
        $file = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso20.bulk.generate.json';
        $jsonFile = json_decode(file_get_contents($file), false);

        dump($jsonFile);
        //        $newData = preg_replace_callback('/%catalog%/', function($match, $dso) use ($mappingCat) {
//            dump($dso);
//        }, $newData);

        return;
    }
}
