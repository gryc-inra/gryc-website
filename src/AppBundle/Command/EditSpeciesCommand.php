<?php
// src/AppBundle/Command/EditSpeciesCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class EditSpeciesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:species:edit')
            ->setDescription('Edit a species')
            ->addArgument(
                'speciesId',
                InputArgument::OPTIONAL,
                'Id of the species'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Si l'utilisateur ne donne pas d'ID lors de la demande d'édition de l'espèce, on lui affiche la liste des espèces
        // et il choisi l'espèce qu'il souhaite modifier
        if (!$input->getArgument('speciesId')) {
            // Appeller la commande bio:species:list, pour donner à l'utilisateur une liste des species
            $listCladeCommand = $this->getApplication()->find('bio:species:list');
            $listCladeCommandInput = new ArrayInput(array('command' => 'bio:species:list'));
            $listCladeCommand->run($listCladeCommandInput, $output);

            // On demande à l'utilisateur quel clade il veut modifier
            $cladeIdQuestion = new Question("\nPlease enter the ID of the species:\n");
            // On récupère l'espèce
            $input->setArgument('speciesId', $helper->ask($input, $output, $cladeIdQuestion));
        }

        $species = $em->getRepository('AppBundle:Species')->findOneById($input->getArgument('speciesId'));

        do {
            // On propose à l'utilisateur de modifier les champs

            // Genus
            $genusQuestion = new Question("\nPlease enter the genus of the species: (actual value: " . $species->getGenus() . ")\n", $species->getGenus());
            $speciesGenus = $helper->ask($input, $output, $genusQuestion);

            // Species
            $speciesQuestion = new Question("\nPlease enter the name of the species: (actual value: " . $species->getSpecies() . ")\n", $species->getSpecies());
            $speciesSpecies = $helper->ask($input, $output, $speciesQuestion);

            // Scientificname
            $scientificNameQuestion = new Question("\nPlease enter the scientific name of the species: (actual value: " . $species->getScientificName() . ")\n", $species->getScientificName());
            $speciesScientificName = $helper->ask($input, $output, $scientificNameQuestion);

            // Lineage
            $lineageQuestion = new Question("\nPlease enter the lineage of the species: (actual value: " . $species->getLineage() . ")\n", $species->getLineage());
            $speciesLineage = $helper->ask($input, $output, $lineageQuestion);

            // geneticCode
            $geneticCodeQuestion = new Question("\nPlease enter the genetic code of the species: (actual value: " . $species->getgeneticCode() . ")\n", $species->getGeneticCode());
            $speciesGeneticCode = $helper->ask($input, $output, $geneticCodeQuestion);

            // mitoCode
            $mitoCodeQuestion = new Question("\nPlease enter the mito code of the species: (actual value: " . $species->getMitoCode() . ")\n", $species->getMitoCode());
            $speciesMitoCode = $helper->ask($input, $output, $mitoCodeQuestion);

            // Synonymes
            $synonymes = implode('; ', $species->getSynonymes());
            $synonymesQuestion = new Question("\nPlease enter synonymes of the species: (use \"; \" as separator)(actual value: " . $synonymes . ")\n", $synonymes);
            $speciesSynonymes = $helper->ask($input, $output, $synonymesQuestion);
            $speciesSynonymes = explode("; ", $speciesSynonymes);

            // Description
            $descriptionQuestion = new Question("\nPlease enter the description of the species: (actual value: " . $species->getDescription() . ")\n", $species->getDescription());
            $speciesDescription = $helper->ask($input, $output, $descriptionQuestion);

            // TaxId
            $taxIdQuestion = new Question("\nPlease enter the TaxId of the species: (actual value: " . $species->getTaxId() . ")\n", $species->getTaxId());
            $speciesTaxId = $helper->ask($input, $output, $taxIdQuestion);

            // On fait un résumé des données récupérées
            $userData = "Scientific name:\t" . $speciesScientificName . "\n";
            $userData .= "Species:\t\t" . $speciesSpecies . "\n";
            $userData .= "Genus:\t\t\t" . $speciesGenus . "\n";
            $userData .= "Lineage:\t\t" . $speciesLineage . "\n";
            $userData .= "Genetic Code:\t\t" . $speciesGeneticCode . "\n";
            $userData .= "Mito Code:\t\t" . $speciesMitoCode . "\n";
            $userData .= "Synonymes:\n";
            if ($speciesSynonymes) {
                foreach ($speciesSynonymes as $synonym) {
                    $userData .= "\t\t\t* " . $synonym . "\n";
                }
            }
            $userData .= "Description:\t\t" . $speciesDescription . "\n";
            $userData .= "TaxId:\t\t\t" . $speciesTaxId . "\n";

            $output->writeln("\n" . $userData);

            // On demande à l'utilisateur si les données sont bonnes ou pas
            $confirmQuestion = new ConfirmationQuestion("Is it correct ? (y/N)\n", false);

            // Si les données ne sont pas bonnes, on recommence la boucle
            if (!$helper->ask($input, $output, $confirmQuestion)) {
                $confirm = false;
            } else {
                $confirm = true;
            }
        } while (!$confirm);

        // On associe les nouvelles valeurs à l'objet
        $species->setGenus($speciesGenus);
        $species->setSpecies($speciesSpecies);
        $species->setScientificName($speciesScientificName);
        $species->setLineage($speciesLineage);
        $species->setGeneticCode($speciesGeneticCode);
        $species->setMitoCode($speciesMitoCode);
        $species->setDescription($speciesDescription);
        $species->setTaxId($speciesTaxId);
        $species->emptySynonymes();
        if ($speciesSynonymes) {
            $species->setSynonymes($speciesSynonymes);
        }

        // On persiste l'objet
        $em->flush();

        $output->writeln('<info>The species was successfully edited</info>');
    }
}
