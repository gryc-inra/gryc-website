<?php
// src/AppBundle/Command/EditSpeciesCommand.php

namespace Grycii\AppBundle\Command;

use Grycii\AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class EditSpeciesCommand extends ContainerAwareCommand
{
    private $cladeList = array();
    private $speciesList = array();
    private $species;

    protected function configure()
    {
        $this
            ->setName('bio:species:edit')
            ->setDescription('Add a species')
            ->setDefinition(array(
                new InputArgument('scientific-name', InputArgument::REQUIRED, 'The scientific name of the species'),
                new InputArgument('genus', InputArgument::REQUIRED, 'The genus of the species'),
                new InputArgument('species', InputArgument::REQUIRED, 'The name of the species'),
                new InputArgument('clade', InputArgument::REQUIRED, 'Name of the clade'),
                new InputArgument('lineages', InputArgument::REQUIRED, 'The lineages of the species'),
                new InputArgument('genetic-code', InputArgument::REQUIRED, 'The genetic code of the species'),
                new InputArgument('mito-code', InputArgument::REQUIRED, 'The mito code of the species'),
                new InputArgument('description', InputArgument::REQUIRED, 'The description of the species'),
                new InputArgument('taxid', InputArgument::OPTIONAL, 'TaxId of the species'),
                new InputArgument('synonymes', InputArgument::OPTIONAL, 'Synonymes of the species'),
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
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        do {
            $questions = array();

            // Appeller la commande bio:species:list, pour donner à l'utilisateur une liste des species
            $listSpeciesCommand = $this->getApplication()->find('bio:species:list');
            $listSpeciesCommandInput = new ArrayInput(array('command' => 'bio:species:list'));
            $listSpeciesCommand->run($listSpeciesCommandInput, $output);

            $question = new Question('Please enter the name of the species: ');
            $question->setAutocompleterValues(array_keys($this->speciesList));
            // On crée un validateur, qui vérifié que le nom entré par l'utilisateur est bien un choix possible
            $question->setValidator(function ($answer) {
                if (!in_array($answer, array_keys($this->speciesList))) {
                    throw new \RuntimeException(
                        'The species doesn\'t exist !'
                    );
                }

                return $this->speciesList[$answer];
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $this->species = $answer;

            $speciesQuestions = new SpeciesQuestions($input, $this->speciesList, $this->cladeList, $this->species);

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

            if (!$input->getArgument('taxid')) {
                $questions['taxid'] = $speciesQuestions->getTaxIdQuestion();
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

            // On utilise le nom scientifique pour déduire le genre et l'espèce
            $scientificNameExploded = explode(' ', $input->getArgument('scientific-name'));
            $input->setArgument('genus', $scientificNameExploded[0]);
            $input->setArgument('species', $scientificNameExploded[1]);

            $output->writeln($speciesQuestions->getSummary($input->getArgument('taxid')));

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
                $input->setArgument('taxid', null);
                $confirm = false;
            } else {
                $confirm = true;
            }
        } while (!$confirm);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $species = $this->species;
        $species->setClade($input->getArgument('clade'));
        $species->setScientificName($input->getArgument('scientific-name'));
        $species->setGenus($input->getArgument('genus'));
        $species->setSpecies($input->getArgument('species'));
        $species->setLineages($input->getArgument('lineages'));
        $species->setGeneticCode($input->getArgument('genetic-code'));
        $species->setMitoCode($input->getArgument('mito-code'));
        $species->setSynonymes($input->getArgument('synonymes'));
        $species->setDescription($input->getArgument('description'));
        $species->setTaxid($input->getArgument('taxid'));

        // On persiste l'objet
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($species);
        $em->flush();

        $output->writeln('<info>The species was successfully added</info>');
    }
}
