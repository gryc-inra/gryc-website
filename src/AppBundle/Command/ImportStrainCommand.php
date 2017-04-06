<?php

// src/AppBundle/Command/ImportStrainCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\DnaSequence;
use AppBundle\Entity\FlatFile;
use AppBundle\Entity\Strain;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class ImportStrainCommand extends ContainerAwareCommand
{
    private $species;
    private $speciesList;
    private $fs;

    protected function configure()
    {
        $this
            ->setName('bio:strain:import')
            ->setAliases(['bio:import:strain'])
            ->setDescription('Species import')
            ->addArgument(
                'dir',
                InputArgument::REQUIRED,
                'Directory with required files: *.json and *_files'
            );
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
                '<error>The given directory doesn\'t exists.</error>'
            );
        }

        if (!$this->fs->exists($input->getArgument('dir').'/strain.json')) {
            throw new \RuntimeException(
                '<error>The json file doesn\'t exists.</error>'
            );
        }

        if (!$this->fs->exists($input->getArgument('dir').'/data')) {
            throw new \RuntimeException(
                '<error>The data directory doesn\'t exists.</error>'
            );
        }

        if (!$this->fs->exists([
                $input->getArgument('dir').'/data/FASTA_CDS_nuc',
                $input->getArgument('dir').'/data/FASTA_CDS_pro',
                $input->getArgument('dir').'/data/EMBL_chr',
                $input->getArgument('dir').'/data/FASTA_chr',
            ])) {
            throw new \RuntimeException(
                '<error>The files directory need 4 directories: FASTA_CDS_nuc, FASTA_CDS_pro, EMBL_chr, FASTA_chr.</error>'
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
        $listSpeciesCommandInput = new ArrayInput(['command' => 'bio:species:list']);
        $listSpeciesCommand->run($listSpeciesCommandInput, $output);

        $question = new Question('Please enter the name of the species: ');
        $question->setAutocompleterValues(array_keys($this->speciesList));
        // Verify that the name of the species is an existing species, if yes return the species object
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

        // Decode the Json file to do an array
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
            // Create a chromosome and add it to the Strain
            $chromosome = new Chromosome();
            $strain->addChromosome($chromosome);

            // Define Chromosome properties
            $chromosome->setName($chromosomeData['name']);
            $chromosome->setAccession($chromosomeData['accession']);
            $chromosome->setDescription($chromosomeData['description']);
            if (null !== $chromosomeData['keywords']) {
                // In the Json file the last keyword have a "." at the end, remove it.
                end($chromosomeData['keywords']);
                $chromosomeData['keywords'][key($chromosomeData['keywords'])] = trim(end($chromosomeData['keywords']), '.');
                reset($chromosomeData['keywords']);
            }

            $chromosome->setCdsCount($chromosomeData['cdsCount']);
            $chromosome->setKeywords($chromosomeData['keywords']);
            $chromosome->setProjectId($chromosomeData['projectId']);
            $chromosome->setDateCreated(new \DateTime($chromosomeData['dateCreated']));
            $chromosome->setNumCreated($chromosomeData['numCreated']);
            $chromosome->setDateReleased(new \DateTime($chromosomeData['dateReleased']));
            $chromosome->setNumReleased($chromosomeData['numReleased']);
            $chromosome->setNumVersion($chromosomeData['numVersion']);
            $chromosome->setLength($chromosomeData['length']);
            $chromosome->setGc($chromosomeData['gc']);
            $chromosome->setSource($chromosomeData['source']);
            $chromosome->setMitochondrial($chromosomeData['mitochondrial']);
            $chromosome->setComment($chromosomeData['comment']);

            // DNA SEQUENCE
            $dnaSequence = new DnaSequence();
            $chromosome->setDnaSequence($dnaSequence);

            $dnaSequence->setDna($chromosomeData['DnaSequence']['seq']);
            // The array in the json haven't key, but the positions in the array is:
            // A, C, G, T, N, other.
            $letterCountKeys = ['A', 'C', 'G', 'T', 'N', 'other'];
            $dnaSequence->setLetterCount(array_combine($letterCountKeys, $chromosomeData['DnaSequence']['letterCount']));

            // FLAT FILES
            if (!$this->fs->exists([
                $input->getArgument('dir').'/data/FASTA_CDS_nuc/'.$chromosome->getName().'.fasta',
                $input->getArgument('dir').'/data/FASTA_CDS_pro/'.$chromosome->getName().'.fasta',
                $input->getArgument('dir').'/data/EMBL_chr/'.$chromosome->getName().'.embl',
                $input->getArgument('dir').'/data/FASTA_chr/'.$chromosome->getName().'.fasta',
            ])) {
                throw new \RuntimeException(
                    '<error>One of the files for '.$chromosome->getName().' is missing in one of this directories: FASTA_CDS_nuc, FASTA_CDS_pro, EMBL_chr, FASTA_chr.</error>'
                );
            }

            $flatFiles = [];

            $flatFiles['FASTA_CDS_nuc'] = new FlatFile();
            $flatFiles['FASTA_CDS_nuc']->setFileSystemPath($input->getArgument('dir').'/data/FASTA_CDS_nuc/'.$chromosome->getName().'.fasta');
            $flatFiles['FASTA_CDS_nuc']->setFeatureType('cds');
            $flatFiles['FASTA_CDS_nuc']->setMolType('nuc');
            $flatFiles['FASTA_CDS_nuc']->setFormat('fsa');

            $flatFiles['FASTA_CDS_pro'] = new FlatFile();
            $flatFiles['FASTA_CDS_pro']->setFileSystemPath($input->getArgument('dir').'/data/FASTA_CDS_pro/'.$chromosome->getName().'.fasta');
            $flatFiles['FASTA_CDS_pro']->setFeatureType('cds');
            $flatFiles['FASTA_CDS_pro']->setMolType('pro');
            $flatFiles['FASTA_CDS_pro']->setFormat('fsa');

            $flatFiles['EMBL_chr'] = new FlatFile();
            $flatFiles['EMBL_chr']->setFileSystemPath($input->getArgument('dir').'/data/EMBL_chr/'.$chromosome->getName().'.embl');
            $flatFiles['EMBL_chr']->setFeatureType('chromosome');
            $flatFiles['EMBL_chr']->setMolType('nuc');
            $flatFiles['EMBL_chr']->setFormat('embl');

            $flatFiles['FASTA_chr'] = new FlatFile();
            $flatFiles['FASTA_chr']->setFileSystemPath($input->getArgument('dir').'/data/FASTA_chr/'.$chromosome->getName().'.fasta');
            $flatFiles['FASTA_chr']->setFeatureType('chromosome');
            $flatFiles['FASTA_chr']->setMolType('nuc');
            $flatFiles['FASTA_chr']->setFormat('fsa');

            foreach ($flatFiles as $flatFile) {
                $chromosome->addFlatFile($flatFile);
            }
        }

        // At the end, attach the Strain to the species
        $this->species->addStrain($strain);

        //Before flush, inform the user that transaction take few time
        $output->writeln('<comment>The transaction start, this may take some time (few minutes). Don\'t panic, take advantage there to have a break :)</comment>');

        // Now we flush it (this is a transaction)
        $this->getContainer()->get('doctrine')->getManager()->flush();
    }
}
