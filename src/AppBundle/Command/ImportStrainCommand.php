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

use AppBundle\Entity\BlastFile;
use AppBundle\Entity\Chromosome;
use AppBundle\Entity\DnaSequence;
use AppBundle\Entity\Feature;
use AppBundle\Entity\FlatFile;
use AppBundle\Entity\Locus;
use AppBundle\Entity\Product;
use AppBundle\Entity\Species;
use AppBundle\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class ImportStrainCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Species
     */
    private $species;

    /**
     * @var array
     */
    private $speciesList;

    /**
     * @var Filesystem
     */
    private $fs;

    public function __construct(EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $this->em = $entityManager;
        $this->fs = $filesystem;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('gryc:strain:import')
            ->setDescription('Strain import')
            ->addArgument(
                'dir',
                InputArgument::REQUIRED,
                'Directory with required files: *.json and *_files'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $error = false;

        // If the user doesn't give a directory, return an error
        if (!$input->getArgument('dir')) {
            $io->error('You may give a directory !');
            $error = true;
        }

        // Idem, if the directory doesn't exists
        if (!$this->fs->exists($input->getArgument('dir'))) {
            $io->error('The given directory doesn\'t exists !');
            $error = true;
        }

        // Test if the strain.json file exists
        if (!$this->fs->exists($input->getArgument('dir').'/strain.json')) {
            $io->error('The json file doesn\'t exists.');
            $error = true;
        }

        // Test id the data folder exists
        if (!$this->fs->exists($input->getArgument('dir').'/data')) {
            $io->error('The data directory doesn\'t exists.');
            $error = true;
        }

        // Test if subdirectory exists
        if (!$this->fs->exists([
                $input->getArgument('dir').'/data/FASTA_CDS_nuc',
                $input->getArgument('dir').'/data/FASTA_CDS_pro',
                $input->getArgument('dir').'/data/EMBL_chr',
                $input->getArgument('dir').'/data/FASTA_chr',
            ])) {
            $io->error('The files directory need 4 directories: FASTA_CDS_nuc, FASTA_CDS_pro, EMBL_chr, FASTA_chr.');
            $error = true;
        }

        $speciesList = $this->em->getRepository('AppBundle:Species')->findAll();
        if (empty($speciesList)) {
            $io->error('No species in the database !');
            $error = true;
        }

        if (true === $error) {
            throw new RuntimeException();
        }

        foreach ($speciesList as $species) {
            $this->speciesList[$species->getScientificName()] = $species;
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // List all the persisted species, call the gryc:species:list command to do it
        $listSpeciesCommand = $this->getApplication()->find('gryc:species:list');
        $listSpeciesCommandInput = new ArrayInput(['command' => 'gryc:species:list']);
        $listSpeciesCommand->run($listSpeciesCommandInput, $output);

        $question = new Question('Please enter the name of the species: ');
        $question->setAutocompleterValues(array_keys($this->speciesList));
        // Verify that the name of the species is an existing species, if yes return the species object
        $question->setValidator(function ($answer) {
            if (!in_array($answer, array_keys($this->speciesList), true)) {
                throw new RuntimeException(
                    'The species doesn\'t exist !'
                );
            }

            return $this->speciesList[$answer];
        });

        $this->species = $this->getHelper('question')->ask($input, $output, $question);

        $confirmQuestion = new ConfirmationQuestion('<question>Do you confirm the importation ? (y/N)</question> ', false);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            throw new RuntimeException(
                '<error>Importation aborted !</error>'
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // First, control if the files is readable
        if (!$file = file_get_contents($input->getArgument('dir').'/strain.json')) {
            throw new RuntimeException(
                '<error>The json file can\'t be read !</error>'
            );
        }

        // Decode the Json file to do an array
        $data = json_decode($file, true);

        // Verify if a strain with this name already exists
        if (null !== $this->em->getRepository('AppBundle:Strain')->findOneBy(['name' => $data['name']])) {
            $io->error('A strain with this name already exists in database !');

            throw new RuntimeException();
        }

        // Create a new strain, and hydrate it
        $strain = new Strain();
        $strain->setName($data['name']);
        $strain->setSynonymes($data['synonyme']);
        $strain->setLength($data['length']);
        $strain->setGc($data['gc']);
        $strain->setStatus($data['status']);
        $strain->setCdsCount($data['cdsCount']);

        // BLAST FILES
        $blastFilesName = ['cds_nucl.nhr', 'cds_nucl.nin', 'cds_nucl.nsq', 'cds_prot.phr', 'cds_prot.pin', 'cds_prot.psq', 'chr.nhr', 'chr.nin', 'chr.nsq'];
        $blastFilesFolder = $input->getArgument('dir').'/data/BLAST';
        $blastFilesPath = array_map(function (&$file) use ($blastFilesFolder) {
            return $file = $blastFilesFolder.'/'.$file;
        }, $blastFilesName);

        if (!$this->fs->exists($blastFilesPath)) {
            throw new RuntimeException(
                'At least one of the blastable files is missing.'
            );
        }

        // Create BlastFiles and associate it to Strain
        foreach ($blastFilesPath as $filePath) {
            $blastFile = new BlastFile();
            $blastFile->setFileSystemPath($filePath);
            $strain->addBlastFile($blastFile);
        }

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

            $dnaSequence->setDna(mb_strtoupper($chromosomeData['DnaSequence']['seq']));
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
                throw new RuntimeException(
                    '<error>One of the files for '.$chromosome->getName().' is missing in one of this directories: FASTA_CDS_nuc, FASTA_CDS_pro, EMBL_chr, FASTA_chr.</error>'
                );
            }

            $fastaCdsNucFile = new FlatFile();
            $fastaCdsNucFile->setFileSystemPath($input->getArgument('dir').'/data/FASTA_CDS_nuc/'.$chromosome->getName().'.fasta');
            $fastaCdsNucFile->setSlug(mb_strtolower($chromosome->getName()).'-cds-nuc.fasta');
            $fastaCdsNucFile->setType('fasta-cds-nuc');
            $chromosome->addFlatFile($fastaCdsNucFile);

            $fastaCdsProFile = new FlatFile();
            $fastaCdsProFile->setFileSystemPath($input->getArgument('dir').'/data/FASTA_CDS_pro/'.$chromosome->getName().'.fasta');
            $fastaCdsProFile->setSlug(mb_strtolower($chromosome->getName()).'-cds-pro.fasta');
            $fastaCdsProFile->setType('fasta-cds-pro');
            $chromosome->addFlatFile($fastaCdsProFile);

            $emblChrFile = new FlatFile();
            $emblChrFile->setFileSystemPath($input->getArgument('dir').'/data/EMBL_chr/'.$chromosome->getName().'.embl');
            $emblChrFile->setSlug(mb_strtolower($chromosome->getName()).'-chr.embl');
            $emblChrFile->setType('embl-chr');
            $chromosome->addFlatFile($emblChrFile);

            $fastaChrFile = new FlatFile();
            $fastaChrFile->setFileSystemPath($input->getArgument('dir').'/data/FASTA_chr/'.$chromosome->getName().'.fasta');
            $fastaChrFile->setSlug(mb_strtolower($chromosome->getName()).'-chr.fasta');
            $fastaChrFile->setType('fasta-chr');
            $chromosome->addFlatFile($fastaChrFile);

            // LOCUS
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

        // Before flush, inform the user that transaction take few time
        $io->text('The transaction begins, this may take a few minutes, please wait...');

        // Now we flush it (this is a transaction)
        $this->em->flush();

        // Add a success message
        $io->success('The strain has been successfully imported !');

        // Call Neighborhood generation command
        $confirmQuestion = new ConfirmationQuestion('<question>Do you want generate neighborhood with 2 neighbours ( on each side) ? (Y/n)</question> ', true);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            $io->note('You need generate neighborhood with the following command: bin/console gryc:strain:neighborhood');

            throw new RuntimeException(
                '<error>Neighborhood generation aborted !</error>'
            );
        }

        $neighborhoodGenerationCommand = $this->getApplication()->find('gryc:strain:neighborhood');
        $neighborhoodGenerationCommandInput = new ArrayInput([
                'command' => 'gryc:strain:neighborhood',
                'strainName' => $strain->getName(),
            ]);
        $neighborhoodGenerationCommand->run($neighborhoodGenerationCommandInput, $output);
    }
}
