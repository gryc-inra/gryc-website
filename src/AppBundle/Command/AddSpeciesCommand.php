<?php
// src/AppBundle/Command/AddSpeciesCommand.php

namespace Grycii\AppBundle\Command;

use Grycii\AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DomCrawler\Crawler;

class AddSpeciesCommand extends ContainerAwareCommand
{
    // This constant contain  the url to the ncbi taxon api
    const NCBI_TAXONOMY_API_LINK = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&id=';

    private $cladeList = array();
    private $speciesList = array();

    protected function configure()
    {
        $this
            ->setName('bio:species:add')
            ->setDescription('Add a species')
            ->setDefinition(array(
                new InputArgument('clade', InputArgument::REQUIRED, 'Name of the clade'),
                new InputArgument('scientific-name', InputArgument::REQUIRED, 'The scientific name of the species'),
                new InputArgument('genus', InputArgument::REQUIRED, 'The genus of the species'),
                new InputArgument('species', InputArgument::REQUIRED, 'The name of the species'),
                new InputArgument('lineages', InputArgument::REQUIRED, 'The lineages of the species'),
                new InputArgument('genetic-code', InputArgument::REQUIRED, 'The genetic code of the species'),
                new InputArgument('mito-code', InputArgument::REQUIRED, 'The mito code of the species'),
                new InputArgument('description', InputArgument::REQUIRED, 'The description of the species'),
                new InputArgument('synonymes', InputArgument::OPTIONAL, 'Synonymes of the species'),
                new InputOption('taxid', null, InputOption::VALUE_OPTIONAL, 'Give the TaxID to autofill the Species'),
            ))
            ->setHelp(<<<EOT
The <info>bio:species:add</info> command creates a species:
  <info>bin/console bio:species:add</info>
This interactive shell will ask you all informations on the species and on witch clade you want link the species.
You can alternatively specify the clade as argument:
  <info>bin/console bio:species:add Yarrowia</info>
You can use an autofill option via the taxid flag:
  <info>bin/console bio:species:add --taxid 4952</info>
EOT
            );
    }

    // Here, prepare some variables used later and some control on the command line argument
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Retrieve all clades stocked in the database
        $clades = $em->getRepository('AppBundle:Clade')->findAll();
        foreach ($clades as $clade) {
            $this->cladeList[$clade->getName()] = $clade;
        }

        // Idem, but for the species
        $species = $em->getRepository('AppBundle:Species')->findAll();
        foreach ($species as $specy) {
            $this->speciesList[$specy->getScientificName()] = $specy;
        }

        // If the user give the clade name in the command line, verify if it exists, if yes put the clade object in the clade argument
        if ($input->getArgument('clade')) {
            if (!in_array($input->getArgument('clade'), array_keys($this->cladeList))) {
                throw new \RuntimeException(
                    'The clade doesn\'t exist !'
                );
            }
            $input->setArgument('clade', $this->cladeList[$input->getArgument('clade')]);
        }

        // If the user give a name to the new species in the command line, verify the 'pattern' of the name and that no species with the same name doesn't already exists
        if ($answer = $input->getArgument('scientific-name')) {
            if (!preg_match('#^[A-Z][a-z]* [a-z]*$#', $answer)) {
                throw new \RuntimeException(
                    'The scientific name have not the goot pattern ! (eg: "Candida albicans")'
                );
            }

            if (array_key_exists($answer, $this->speciesList)) {
                throw new \RuntimeException(
                    'This species already exists !'
                );
            }
        }

        // If the user use the taxid option
        if ($input->getOption('taxid')) {
            // Retrieve the page content (xml code)
            $xmlString = file_get_contents(self::NCBI_TAXONOMY_API_LINK.$input->getOption('taxid'));

            // Create a crawler and give the xml code to it
            $crawler = new Crawler($xmlString);

            // Count the number of taxon tag, if different of 0 there are contents, else the document is empty, it's because the Taxon Id doesn't exists
            if (0 !== $crawler->filterXPath('//TaxaSet/Taxon')->count()) {
                // If the tag Rank contain 'species', the Id match on a species, else, it's not correct.
                if ('species' === $crawler->filterXPath('//TaxaSet/Taxon/Rank')->text()) {
                    // Use the crawler to crawl the document and fill the arguments
                    $input->setArgument('scientific-name', $crawler->filterXPath('//TaxaSet/Taxon/ScientificName')->text());

                    // Here control if the scientific name exist in the species list, we can't have 2 species with the same scientific name (Genus and species)
                    if (array_key_exists($input->getArgument('scientific-name'), $this->speciesList)) {
                        throw new \RuntimeException(
                            'This species already exists !'
                        );
                    }

                    $input->setArgument('genetic-code', $crawler->filterXPath('//TaxaSet/Taxon/GeneticCode/GCId')->text());
                    $input->setArgument('mito-code', $crawler->filterXPath('//TaxaSet/Taxon/MitoGeneticCode/MGCId')->text());
                    $input->setArgument('lineages', explode('; ', $crawler->filterXPath('//TaxaSet/Taxon/Lineage')->text()));

                    // He re count the number of synonym tag, if the count is different to 0, there are synonymes
                    if (0 !== $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->count()) {
                        // Use a closure on the tag Synonym to extract all synonymes and fill an array
                        $synonymes = $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->each(function (Crawler $node, $i) {
                            return $node->text();
                        });
                        $input->setArgument('synonymes', $synonymes);
                    }
                } else {
                    throw new \RuntimeException(
                        'This ID doesn\'t match on a species.'
                    );
                }
            } else {
                throw new \RuntimeException(
                    'This ID doesn\'t exist.'
                );
            }
        }
    }

    // Here we prepare the question to the user, if he doesn't give all the arguments in the command line
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        do {
            $questions = array();
            $speciesQuestions = new SpeciesQuestions($input, $this->speciesList, $this->cladeList);

            if (!$input->getArgument('clade')) {
                // List all the persisted clades, call the bio:clade:list command to do it
                $listCladeCommand = $this->getApplication()->find('bio:clade:list');
                $listCladeCommandInput = new ArrayInput(array('command' => 'bio:clade:list'));
                $listCladeCommand->run($listCladeCommandInput, $output);

                $questions['clade'] = $speciesQuestions->getCladeQuestion();
            }

            if (!$input->getArgument('scientific-name')) {
                $questions['scientific-name'] = $speciesQuestions->getScientificNameQuestion();
            }

            if (!$input->getArgument('lineages')) {
                $questions['lineages'] = $speciesQuestions->getLineageQuestion();
            }

            if (!$input->getArgument('genetic-code')) {
                $questions['genetic-code'] = $speciesQuestions->getGeneticCodeQuestion();
            }

            if (!$input->getArgument('mito-code')) {
                $questions['mito-code'] = $speciesQuestions->getMitoCodeQuestion();
            }

            if (!$input->getArgument('synonymes')) {
                $questions['synonymes'] = $speciesQuestions->getSynonymesQuestion();
            }

            if (!$input->getArgument('description')) {
                $questions['description'] = $speciesQuestions->getDescriptionQuestion();
            }

            foreach ($questions as $name => $question) {
                $answer = $this->getHelper('question')->ask($input, $output, $question);
                $input->setArgument($name, $answer);
            }

            // Explode the scientific name to retrieve: genus and species
            $scientificNameExploded = explode(' ', $input->getArgument('scientific-name'));
            $input->setArgument('genus', $scientificNameExploded[0]);
            $input->setArgument('species', $scientificNameExploded[1]);

            // Ask to the user if he is sure of his answers.
            $output->writeln($speciesQuestions->getSummary($input->getOption('taxid')));

            if (!$this->getHelper('question')->ask($input, $output, $speciesQuestions->getConfirmationQuestion())) {
                $input->setArgument('clade', null);
                $input->setArgument('scientific-name', null);
                $input->setArgument('genus', null);
                $input->setArgument('species', null);
                $input->setArgument('lineages', null);
                $input->setArgument('genetic-code', null);
                $input->setArgument('mito-code', null);
                $input->setArgument('synonymes', null);
                $input->setArgument('description', null);
                $input->setOption('taxid', null);
                $confirm = false;
            } else {
                $confirm = true;
            }
        } while (!$confirm);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create the Species object and set all the attributes
        $species = new Species();
        $species->setClade($input->getArgument('clade'));
        $species->setScientificName($input->getArgument('scientific-name'));
        $species->setGenus($input->getArgument('genus'));
        $species->setSpecies($input->getArgument('species'));
        $species->setLineages($input->getArgument('lineages'));
        $species->setGeneticCode($input->getArgument('genetic-code'));
        $species->setMitoCode($input->getArgument('mito-code'));
        $species->setSynonymes($input->getArgument('synonymes'));
        $species->setDescription($input->getArgument('description'));
        $species->setTaxid($input->getOption('taxid'));

        // Persist and flush the object in the database
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($species);
        $em->flush();

        $output->writeln('<info>The species was successfully added</info>');
    }
}
