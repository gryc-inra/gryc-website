<?php
// src/AppBundle/Command/ListCladeCommand.php

namespace Grycii\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCladeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:clade:list')
            ->setDescription('Clades list')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $listClades = $em->getRepository('AppBundle:Clade')->findAll();

        $table = new Table($output);
        $table->setHeaders(array('ID', 'Name', 'Description', 'Main Clade ?'));

        if (empty($listClades)) {
            $table->setRows(array(
                array(new TableCell('There is no clade', array('colspan' => 4))),
            ));
        } else {
            foreach ($listClades as $clade) {
                $table->addRow(array($clade->getId(), $clade->getName(), $clade->getDescription(), $clade->isMainCladeToString()));
            }
        }
        $table->render();
    }
}
