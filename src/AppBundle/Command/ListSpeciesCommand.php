<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
            ->setName('gryc:species:list')
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
