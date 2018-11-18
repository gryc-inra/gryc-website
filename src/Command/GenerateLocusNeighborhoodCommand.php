<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Command;

use App\Entity\Neighbour;
use App\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class GenerateLocusNeighborhoodCommand extends ContainerAwareCommand
{
    const DEFAULT_NB_NEIGHBOURS = 2;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Strain
     */
    private $strain;

    public function __construct(EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('gryc:strain:neighborhood')
            ->setDescription('Generate the locus neighborhood for a specific strain')
            ->addArgument(
                'strainName',
                InputArgument::REQUIRED,
                'The strain name'
            )
            ->addArgument(
                'nbNeighbours',
                InputArgument::OPTIONAL,
                'The number of neighbours for each locus on each side',
                self::DEFAULT_NB_NEIGHBOURS
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $error = false;

        // Retrieve the strain
        if (null === $this->strain = $this->entityManager->getRepository('App:Strain')->findOneBy(['name' => $input->getArgument('strainName')])) {
            $error = true;
            $io->error('This strain doesn\'t exists !');
        }

        // Verify the number of neighbours
        if (!\is_int($input->getArgument('nbNeighbours')) && !$input->getArgument('nbNeighbours') > 0) {
            $error = true;
            $io->error('The number of neighbours must be an integer and > to 0 !');
        }

        if (true === $error) {
            throw new RuntimeException();
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('The neighborhood will be generated with '.$input->getArgument('nbNeighbours').' neighbour(s) on each side.');

        $confirmQuestion = new ConfirmationQuestion('<question>Do you confirm the neihborhood generation ? (Y/n)</question> ', true);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            $io->error('Neighborhood generation aborted !');

            throw new RuntimeException();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Get stain's chromosomes
        $chromosomes = $this->strain->getChromosomes();

        // For each chromosome
        foreach ($chromosomes as $chromosome) {
            // Get the Locus
            $locusList = $this->entityManager->getRepository('App:Locus')->findBy(
                ['chromosome' => $chromosome],
                ['start' => 'ASC']
            );

            $nbLocus = \count($locusList);

            // For each Locus
            for ($i = 0; $i < $nbLocus; ++$i) {
                // Clear old neighbours
                $locusList[$i]->clearNeighbours();

                // Create a fake neighbour, that is the actual Locus
                $neighbour = new Neighbour();
                $neighbour->setPosition(0);
                $neighbour->setNeighbour($locusList[$i]);
                $neighbour->setNumberNeighbours($input->getArgument('nbNeighbours'));

                $locusList[$i]->addNeighbour($neighbour);

                // For each neighbour
                $nbNeighbours = $input->getArgument('nbNeighbours');
                for ($j = 1; $j <= $nbNeighbours; ++$j) {
                    // Create 2 neighbours: the downstream and upstream
                    // If the downstream neighbour exists
                    if ($i - $j >= 0) {
                        $neighbour = new Neighbour();
                        $neighbour->setPosition(-$j);
                        $neighbour->setNeighbour($locusList[$i - $j]);
                        $neighbour->setNumberNeighbours($input->getArgument('nbNeighbours'));

                        $locusList[$i]->addNeighbour($neighbour);
                    }

                    // If the upstream neighbour exists
                    if ($i + $j < $nbLocus) {
                        $neighbour = new Neighbour();
                        $neighbour->setPosition($j);
                        $neighbour->setNeighbour($locusList[$i + $j]);
                        $neighbour->setNumberNeighbours($input->getArgument('nbNeighbours'));

                        $locusList[$i]->addNeighbour($neighbour);
                    }
                }
            }
        }

        // Before flush, inform the user that transaction take few time
        $io->text('The transaction begins, this may take a few minutes, please wait...');

        // Flush data
        $this->entityManager->flush();

        $io->success('The strain neighborhood have been successfully generated !');
    }
}
