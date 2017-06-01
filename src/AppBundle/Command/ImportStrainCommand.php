<?php

namespace AppBundle\Command;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\DnaSequence;
use AppBundle\Entity\Feature;
use AppBundle\Entity\FlatFile;
use AppBundle\Entity\Locus;
use AppBundle\Entity\Product;
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
    private $projectDir;

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;

        parent::__construct();
    }

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

        // Before process we need to increase the memory limit, because we want to a transaction on a complete Genome
        ini_set('memory_limit', $this->getContainer()->getParameter('genomes_memory_limit'));

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

            $dnaSequence->setDna(strtoupper($chromosomeData['DnaSequence']['seq']));
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

            foreach ($chromosomeData['locus'] as $locusData) {
                $locus = new Locus();
                $chromosome->addLocus($locus);

                $locus->setStrand($locusData['strand']);
                $locus->setProduct($locusData['product']);
                $locus->setName($locusData['name']);
                $locus->setAnnotation($locusData['annotation']);
                $locus->setType($locusData['type']);
                $locus->setCoordinates($locusData['coordinates']);
                $locus->setNote($locusData['note']);
                $locus->setStart($locusData['start']);
                $locus->setEnd($locusData['end']);

                foreach ($locusData['feature'] as $featureData) {
                    $feature = new Feature();
                    $locus->addFeature($feature);

                    $feature->setStrand($featureData['strand']);
                    $feature->setProduct($featureData['product']);
                    $feature->setName($featureData['name']);
                    $feature->setAnnotation($featureData['annotation']);
                    $feature->setType($featureData['type']);
                    $feature->setCoordinates($featureData['coordinates']);
                    $feature->setNote($featureData['note']);
                    $feature->setStart($featureData['start']);
                    $feature->setEnd($featureData['end']);

                    foreach ($featureData['product_feature'] as $productData) {
                        $product = new Product();
                        $feature->addProductsFeatures($product);

                        $product->setStrand($productData['strand']);
                        $product->setProduct($productData['product']);
                        $product->setName($productData['name']);
                        $product->setAnnotation($productData['annotation']);
                        $product->setType($productData['type']);
                        $product->setCoordinates($productData['coordinates']);
                        $product->setNote($productData['note']);
                        $product->setStart($productData['start']);
                        $product->setEnd($productData['end']);
                        isset($productData['translation']) ? $product->setTranslation($productData['translation']) : null;
                    }
                }
            }
        }

        // At the end, attach the Strain to the species
        $this->species->addStrain($strain);

        // Test if Blast files exists
        $blastFilesName = ['cds_nucl.nhr', 'cds_nucl.nin', 'cds_nucl.nsq', 'cds_prot.phr', 'cds_prot.pin', 'cds_prot.psq', 'chr.nhr', 'chr.nin', 'chr.nsq'];
        $blastFilesFolder = $input->getArgument('dir').'/data/BLAST';
        $blastFilesTargetFolder = $this->projectDir.'/files/blast';

        $blastFiles = array_map(function (&$file) use ($blastFilesFolder) {
            return $file = $blastFilesFolder.'/'.$file;
        }, $blastFilesName);

        if (!$this->fs->exists($blastFiles)) {
            throw new \RuntimeException(
                'At least one of the blastable files is missing.'
            );
        }

        // Before flush, inform the user that transaction take few time
        $output->writeln('<comment>The transaction start, this may take some time (few minutes). Don\'t panic, take advantage there to have a break :)</comment>');

        // Now we flush it (this is a transaction)
        $this->getContainer()->get('doctrine')->getManager()->flush();

        // At the end of the transaction, we move blastable files
        $i = 0;
        foreach ($blastFiles as $file) {
            $this->fs->copy($file, $blastFilesTargetFolder.'/'.$strain->getId().'_'.$blastFilesName[$i]);
            ++$i;
        }
    }
}
