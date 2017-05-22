<?php

// src/AppBundle/Command/ListSpeciesCommand.php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListSpeciesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:species:list')
            ->setDescription('Species list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $listSpecies = $em->getRepository('AppBundle:Species')->findBy([], ['scientificName' => 'ASC']);

        $table = new Table($output);
        $table->setHeaders(['ID', 'Genus', 'Species']);

        if (empty($listSpecies)) {
            $table->setRows([
                [new TableCell('There is no species', ['colspan' => 3])],
            ]);
        } else {
            foreach ($listSpecies as $species) {
                $table->addRow([$species->getId(), $species->getGenus(), $species->getSpecies()]);
            }
        }
        $table->render();
    }
}
