<?php
// src/AppBundle/Command/ImportStrainCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\DnaSequence;
use AppBundle\Entity\Strain;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ImportStrainCommand extends ContainerAwareCommand
{
    private $species;
    private $speciesList;

    protected function configure()
    {
        $this
            ->setName('bio:strain:import')
            ->setAliases(array('bio:import:strain'))
            ->setDescription('Species import')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'File to import'
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        if (!$input->getArgument('file')) {
            throw new \RuntimeException(
                '<error>You may give a file !</error>'
            );
        }

        $speciesList = $this->getContainer()->get('doctrine')->getManager()->getRepository('AppBundle:Species')->findAll();
        if (empty($speciesList)) {
            throw new \RuntimeException(
                '<error>No species in the database !</error>'
            );
        }
        foreach ($speciesList as $species) {
            $this->speciesList[$species->getScientificName()] = $species;
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        // List all the persisted species, call the bio:species:list command to do it
        $listSpeciesCommand = $this->getApplication()->find('bio:species:list');
        $listSpeciesCommandInput = new ArrayInput(array('command' => 'bio:species:list'));
        $listSpeciesCommand->run($listSpeciesCommandInput, $output);

        $question = new Question('Please enter the name of the species: ');
        $question->setAutocompleterValues(array_keys($this->speciesList));
        // Verify that the name of the clade is an existing clade, if yes return the clade object
        $question->setValidator(function ($answer) {
            if (!in_array($answer, array_keys($this->speciesList))) {
                throw new \RuntimeException(
                    'The species doesn\'t exist !'
                );
            }

            return $this->speciesList[$answer];
        });

        $this->species = $this->getHelper('question')->ask($input, $output, $question);

        $confirmQuestion = new ConfirmationQuestion('<question>Do you confirm the importation ? (y/N)</question> ', false);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            throw new \RuntimeException(
                '<error>Importation aborted !</error>'
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // First, control if the files is readable
        if (!$file = file_get_contents($input->getArgument('file'))) {
            throw new \RuntimeException(
                '<error>This file doesn\'t exist !</error>'
            );
        }

        // Decode the Json file to do a array
        $data = json_decode($file, true);

        // Create a new strain, and hydrate it
        $strain = new Strain();
        $strain->setName($data['name']);
        $strain->setSynonymes($data['synonyme']);
        $strain->setLength($data['length']);
        $strain->setGc($data['gc']);
        $strain->setStatus($data['status']);
        $strain->setCdsCount($data['cdsCount']);

        // For each chromosome, create a new chromosome
        foreach ($data['chromosome'] as $chromosomeData) {
            $dnaSequence = new DnaSequence();
            $dnaSequence->setDna($chromosomeData['DnaSequence']['seq']);
            //$dnaSequence->setDna('AATTCCGG');
            // The array in the json haven't key, but the positions in the array is:
            // A, C, G, T, N, other.
            $letterCountKeys = array('A', 'C', 'G', 'T', 'N', 'other');
            $dnaSequence->setLetterCount(array_combine($letterCountKeys, $chromosomeData['DnaSequence']['letterCount']));

            $chromosome = new Chromosome();
            $chromosome->setName($chromosomeData['name']);
            if ($chromosomeData['accession']) {
                $chromosome->setAccession($chromosomeData['accession']);
            }
            $chromosome->setDescription($chromosomeData['description']);

            if (null !== $chromosomeData['keywords']) {
                // In the Json file the last keyword have a "." at the end, remove it.
                end($chromosomeData['keywords']);
                $chromosomeData['keywords'][key($chromosomeData['keywords'])] = trim(end($chromosomeData['keywords']), ".");
                reset($chromosomeData['keywords']);
            }

            $chromosome->setKeywords($chromosomeData['keywords']);
            $chromosome->setProjectId($chromosomeData['projectId']);
            $chromosome->setDateCreated(new \DateTime($chromosomeData['dateCreated']));
            $chromosome->setNumCreated($chromosomeData['numCreated']);
            $chromosome->setDateReleased(new \DateTime($chromosomeData['dateReleased']));
            $chromosome->setNumReleased($chromosomeData['numReleased']);
            $chromosome->setNumVersion($chromosomeData['numVersion']);
            $chromosome->setLength($chromosomeData['length']);
            $chromosome->setGc($chromosomeData['gc']);
            $chromosome->setCdsCount($chromosomeData['cdsCount']);
            $chromosome->setMitochondrial($chromosomeData['mitochondrial']);
            $chromosome->setComment($chromosomeData['comment']);
            $chromosome->setDnaSequence($dnaSequence);

            // Attach the chromosome to the strain
            $strain->addChromosome($chromosome);
        }

        // At the end, attach the Strain to the species
        $this->species->addStrain($strain);

        $em = $this->getContainer()->get('doctrine')->getManager();

        // Persist the species (With persist cascade in the relations, doctrine save all the informations)
        $em->persist($this->species);

        //Before flush, inform the user that transaction take few time
        $output->writeln('<comment>The transaction start, this may take some time (few minutes). Don\'t panic, take advantage there to have a break :)</comment>');

        // Now we flush it (this is a transaction)
        $em->flush();

        $output->writeln('<info>The strain was successfully imported !</info>');
    }
}
