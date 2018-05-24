<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BulkImportCommand extends ContainerAwareCommand
{
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

        $dataFile =  $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso6.bulk.json';

        $contentFile = file_get_contents($dataFile);

        // Change ID
        $newData = preg_replace_callback('/%randId%/', function() {
            $datetime = new \DateTime();
            $prefix = $datetime->getTimestamp();
            return md5(uniqid($prefix));
        }, $contentFile);

        // Change Catalog
        $mappingCat = ['NGC' => 'ngc', 'IC' => 'ic'];

        $dataNewFile =  $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources/data/dso.generate.data.json';
        file_put_contents($dataNewFile, $newData);
    }
}
