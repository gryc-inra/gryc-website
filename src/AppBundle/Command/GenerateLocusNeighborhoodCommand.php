<?php

namespace AppBundle\Command;

use AppBundle\Entity\Neighbour;
use AppBundle\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
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

    /**
     * @var array
     */
    private $strainList;

    /**
     * @var int
     */
    private $nbNeighbours;

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
                'nbNeighbours',
                InputArgument::OPTIONAL,
                'The number of neighbours for each locus on each side (default: '.self::DEFAULT_NB_NEIGHBOURS.')'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $error = false;

        // If the user don't set a number of neighbours, use the default value
        $this->nbNeighbours = $input->getArgument('nbNeighbours');
        if (null === $this->nbNeighbours && !is_int($this->nbNeighbours) && !$this->nbNeighbours > 0) {
            $this->nbNeighbours = self::DEFAULT_NB_NEIGHBOURS;
        }

        $strainList = $this->entityManager->getRepository('AppBundle:Strain')->findAll();
        if (empty($strainList)) {
            $io->error('No strains in the database.');
            $error = true;
        }

        if (true === $error) {
            throw new RuntimeException();
        }

        foreach ($strainList as $strain) {
            $this->strainList[$strain->getName()] = $strain;
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $error = false;

        $question = new Question('Please enter the name of the strain: ');
        $question->setAutocompleterValues(array_keys($this->strainList));
        // Verify that the name of the species is an existing species, if yes return the species object
        $question->setValidator(function ($answer) {
            if (!in_array($answer, array_keys($this->strainList), true)) {
                throw new RuntimeException(
                    'The strain doesn\'t exist !'
                );
            }

            return $this->strainList[$answer];
        });

        $this->strain = $this->getHelper('question')->ask($input, $output, $question);

        $io->note('The neighborhood will be generated with '.$this->nbNeighbours.' neighbour(s) on each side.');

        $confirmQuestion = new ConfirmationQuestion('<question>Do you confirm the neihborhood generation ? (y/N)</question> ', false);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            $io->error('Neighborhood generation aborted !');
            $error = true;
        }

        if (true === $error) {
            throw new RuntimeException();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Get the chromosomes of the strain
        $chromosomes = $this->strain->getChromosomes();

        // For each chromosome
        foreach ($chromosomes as $chromosome) {
            // Get the Locus
            $locusList = $this->entityManager->getRepository('AppBundle:Locus')->findBy(
                ['chromosome' => $chromosome],
                ['start' => 'ASC']
            );

            $nbLocus = count($locusList);

            // For each Locus
            for ($i = 0; $i < $nbLocus; ++$i) {
                // Clear old neighbours
                $locusList[$i]->clearNeighbours();

                // Create a fake neighbour, that is the actual Locus
                $neighbour = new Neighbour();
                $neighbour->setPosition(0);
                $neighbour->setNeighbour($locusList[$i]);
                $neighbour->setNumberNeighbours($this->nbNeighbours);

                $locusList[$i]->addNeighbour($neighbour);

                // For each neighbour
                for ($j = 1; $j <= $this->nbNeighbours; ++$j) {
                    // Create 2 neighbours: the downstream and upstream
                    // If the downstream neighbour exists
                    if ($i - $j >= 0) {
                        $neighbour = new Neighbour();
                        $neighbour->setPosition(-$j);
                        $neighbour->setNeighbour($locusList[$i - $j]);
                        $neighbour->setNumberNeighbours($this->nbNeighbours);

                        $locusList[$i]->addNeighbour($neighbour);
                    }

                    // If the upstream neighbour exists
                    if ($i + $j < $nbLocus) {
                        $neighbour = new Neighbour();
                        $neighbour->setPosition($j);
                        $neighbour->setNeighbour($locusList[$i + $j]);
                        $neighbour->setNumberNeighbours($this->nbNeighbours);

                        $locusList[$i]->addNeighbour($neighbour);
                    }
                }
            }
        }

        // Flush data
        $this->entityManager->flush();

        $io->success('The strain neighborhood have been successfully generated !');
    }
}
