<?php
// src/AppBundle/Command/ImportStrainCommand.php

namespace Grycii\AppBundle\Command;

use Grycii\AppBundle\Entity\Chromosome;
use Grycii\AppBundle\Entity\DnaSequence;
use Grycii\AppBundle\Entity\FlatFile;
use Grycii\AppBundle\Entity\Strain;
use Proxies\__CG__\Grycii\AppBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ImportStrainCommand extends ContainerAwareCommand
{
    private $species;
    private $speciesList;
    private $fs;

    protected function configure()
    {
        $this
            ->setName('bio:strain:import')
            ->setAliases(array('bio:import:strain'))
            ->setDescription('Species import')
            ->addArgument(
                'dir',
                InputArgument::REQUIRED,
                'Directory with required files: *.json and *_files'
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->fs = new Filesystem();

        // If the user doen't give a directory, return an error
        if (!$input->getArgument('dir')) {
            throw new \RuntimeException(
                '<error>You may give a directory !</error>'
            );
        }

        // Idem, if the directory doesn't exists
        if (!$this->fs->exists($input->getArgument('dir'))) {
            throw new \RuntimeException(
                '<error>The given directory doen\'t exists.</error>'
            );
        }

        if (!$this->fs->exists($input->getArgument('dir').'/strain.json')) {
            throw new \RuntimeException(
                '<error>The json file doen\'t exists.</error>'
            );
        }

        if (!$this->fs->exists($input->getArgument('dir').'/files')) {
            throw new \RuntimeException(
                '<error>The files directory doen\'t exists.</error>'
            );
        }

        if (!$this->fs->exists(array(
                $input->getArgument('dir').'/files/CDSnuc',
                $input->getArgument('dir').'/files/CDSpro',
                $input->getArgument('dir').'/files/EMBL',
                $input->getArgument('dir').'/files/Fasta',
            ))) {
            throw new \RuntimeException(
                '<error>The files directory need 4 directories: CDSnuc, CDSpro, EMBL, Fasta.</error>'
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
        if (!$file = file_get_contents($input->getArgument('dir').'/strain.json')) {
            throw new \RuntimeException(
                '<error>The json file can\'t be read !</error>'
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
            // CHROMOSOME
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

            // DNA SEQUENCE
            $dnaSequence = new DnaSequence();
            //$dnaSequence->setDna($chromosomeData['DnaSequence']['seq']);
            $dnaSequence->setDna('AAT');
            // The array in the json haven't key, but the positions in the array is:
            // A, C, G, T, N, other.
            $letterCountKeys = array('A', 'C', 'G', 'T', 'N', 'other');
            $dnaSequence->setLetterCount(array_combine($letterCountKeys, $chromosomeData['DnaSequence']['letterCount']));
            $chromosome->setDnaSequence($dnaSequence);

            // FLAT FILES
            if (!$this->fs->exists(array(
                $input->getArgument('dir').'/files/CDSnuc/'.$chromosome->getName().'.fsa',
                $input->getArgument('dir').'/files/CDSpro/'.$chromosome->getName().'.fsa',
                $input->getArgument('dir').'/files/EMBL/'.$chromosome->getName().'.embl',
                $input->getArgument('dir').'/files/Fasta/'.$chromosome->getName().'.fsa',
            ))) {
                throw new \RuntimeException(
                    '<error>One of the files for '.$chromosome->getName().' is missing in one of this directories: CDSnuc, CDSpro, EMBL, Fasta.</error>'
                );
            }

            $flatFiles = array();

            $flatFiles['CDSnuc'] = new FlatFile();
            $flatFiles['CDSnuc']->setFileSystemPath($input->getArgument('dir').'/files/CDSnuc/'.$chromosome->getName().'.fsa');
            $flatFiles['CDSnuc']->setFeatureType('cds');
            $flatFiles['CDSnuc']->setMolType('nuc');
            $flatFiles['CDSnuc']->setFormat('fsa');

            $flatFiles['CDSpro'] = new FlatFile();
            $flatFiles['CDSpro']->setFileSystemPath($input->getArgument('dir').'/files/CDSpro/'.$chromosome->getName().'.fsa');
            $flatFiles['CDSpro']->setFeatureType('cds');
            $flatFiles['CDSpro']->setMolType('pro');
            $flatFiles['CDSpro']->setFormat('fsa');

            $flatFiles['embl'] = new FlatFile();
            $flatFiles['embl']->setFileSystemPath($input->getArgument('dir').'/files/EMBL/'.$chromosome->getName().'.embl');
            $flatFiles['embl']->setFeatureType('chromosome');
            $flatFiles['embl']->setMolType('nuc');
            $flatFiles['embl']->setFormat('embl');

            $flatFiles['fasta'] = new FlatFile();
            $flatFiles['fasta']->setFileSystemPath($input->getArgument('dir').'/files/Fasta/'.$chromosome->getName().'.fsa');
            $flatFiles['fasta']->setFeatureType('chromosome');
            $flatFiles['fasta']->setMolType('nuc');
            $flatFiles['fasta']->setFormat('fsa');

            foreach ($flatFiles as $flatFile) {
                $chromosome->addFlatFile($flatFile);
            }

            // CHROMOSOME ON THE STRAIN
            $strain->addChromosome($chromosome);
        }

        // At the end, attach the Strain to the species
        $this->species->addStrain($strain);

        //Before flush, inform the user that transaction take few time
        $output->writeln('<comment>The transaction start, this may take some time (few minutes). Don\'t panic, take advantage there to have a break :)</comment>');

        // Now we flush it (this is a transaction)
        $this->getContainer()->get('doctrine')->getManager()->flush();

        $output->writeln('<info>The strain was successfully imported !</info>');
    }
}
