<?php
// src/AppBundle/Command/AddSpeciesCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AddSpeciesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:species:add')
            ->setDescription('Add a species')
            ->addArgument(
                'ncbi',
                InputArgument::OPTIONAL,
                'NCBI taxonomy ID'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $species = new Species();

        do {
            // On witch clade link the species ?
            $em = $this->getContainer()->get('doctrine')->getManager();
            $cladesListObject = $em->getRepository('AppBundle:Clade')->findAll();
            $clades = array();

            foreach ($cladesListObject as $clade) {
                $clades[] = $clade->getName();
            }

            $speciesCladeQuestion = new Question("Please enter the name of a clade:\n");
            $speciesCladeQuestion->setAutocompleterValues($clades);
            $species->setClade(
                $em->getRepository('AppBundle:Clade')->findOneByName($helper->ask($input, $output, $speciesCladeQuestion))
            );

            // Scientificname
            $scientificNameQuestion = new Question("\nPlease enter the scientific name of the species:\n");
            $species->setScientificName($helper->ask($input, $output, $scientificNameQuestion));

            // Species
            $speciesQuestion = new Question("\nPlease enter the name of the species:\n");
            $species->setSpecies($helper->ask($input, $output, $speciesQuestion));

            // Genus
            $genusQuestion = new Question("\nPlease enter the genus of the species:\n");
            $species->setGenus($helper->ask($input, $output, $genusQuestion));

            // Lineage
            $lineageQuestion = new Question("\nPlease enter the lineage of the species:\n");
            $species->setLineage($helper->ask($input, $output, $lineageQuestion));

            // geneticCode
            $geneticCodeQuestion = new Question("\nPlease enter the genetic code of the species: (default: 1)\n", 1);
            $species->setGeneticCode($helper->ask($input, $output, $geneticCodeQuestion));

            // mitoCode
            $mitoCodeQuestion = new Question("\nPlease enter the mito code of the species: (default: 3)\n", 3);
            $species->setMitoCode($helper->ask($input, $output, $mitoCodeQuestion));

            // Synonymes
            $synonymesQuestion = new Question("\nPlease enter the synonyme of the species: (default: null)\n", null);
            $species->setSynonymes($helper->ask($input, $output, $synonymesQuestion));

            // Description
            $descriptionQuestion = new Question("\nPlease enter the description of the species: (default: null)\n", null);
            $species->setDescription($helper->ask($input, $output, $descriptionQuestion));

            // TaxId
            $taxIdQuestion = new Question("\nPlease enter the taxId of the species: (default: null)\n", null);
            $species->setTaxid($helper->ask($input, $output, $taxIdQuestion));

            // Resume
            $userData = "Clade:\t\t\t" . $species->getClade()->getName() . "\n";
            $userData .= "Scientific name:\t" . $species->getScientificName() . "\n";
            $userData .= "Species:\t\t" . $species->getSpecies() . "\n";
            $userData .= "Genus:\t\t\t" . $species->getGenus() . "\n";
            $userData .= "Lineage:\t\t" . $species->getLineage() . "\n";
            $userData .= "Genetic Code:\t\t" . $species->getGeneticCode() . "\n";
            $userData .= "Mito Code:\t\t" . $species->getMitoCode() . "\n";
            $userData .= "Synonymes:\t\t" . $species->getSynonymes() . "\n";
            $userData .= "Description:\t\t" . $species->getDescription() . "\n";
            $userData .= "TaxId:\t\t" . $species->getTaxid() . "\n";

            $output->writeln("\n" . $userData);

            // User Confirmation before flush it
            $confirmQuestion = new ConfirmationQuestion('Is it correct ? (y/N)', false);
            if (!$helper->ask($input, $output, $confirmQuestion)) {
                $confirm = false;
            } else {
                $confirm = true;
            }
        } while(!$confirm);

        // Persist and flush
        $em->persist($species);
        $em->flush();

        $output->writeln('<info>The species was successfully added</info>');
    }
}
