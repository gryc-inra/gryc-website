<?php
// src/AppBundle/Command/AddCladeCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Clade;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AddCladeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:clade:add')
            ->setDescription('Add a clade')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Name of the clade'
            )
            ->addArgument(
                'description',
                InputArgument::OPTIONAL,
                'Description of the clade'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Créer le helper
        $helper = $this->getHelper('question');

        do
        {
            // On crée l'objet à remplir
            $clade = new Clade();

            // On demande à l'utilisateur des informations pour hydrater l'objet

            // CladeName
            if ($input->getArgument('name')) {
                $clade->setName($input->getArgument('name'));
            } else {
                $cladeNameQuestion = new Question("\nPlease enter the name of the clade:\n");
                $clade->setName($helper->ask($input, $output, $cladeNameQuestion));
            }

            // CladeDescription
            if ($input->getArgument('description')) {
                $clade->setDescription($input->getArgument('description'));
            } else {
                $cladeDescriptionQuestion = new Question("\nPlease enter the description of the clade:\n");
                $clade->setDescription($helper->ask($input, $output, $cladeDescriptionQuestion));
            }

            // MainClade
            $isMainCladeQuestion = new ConfirmationQuestion("\nIs it a Main Clade ? (y/N)\n", false);
            $clade->setMainClade($helper->ask($input, $output, $isMainCladeQuestion));

            // On fait un résumé des données récupérées
            $userData = "Clade name:\t" . $clade->getName() . "\n";
            $userData .= ($clade->getMainClade()) ? "Main clade:\tYes\n" : "Main clade:\tNo\n";
            $userData .= "Description:\n" . $clade->getDescription() . "\n";

            $output->writeln("\n".$userData);

            // On demande à l'utilisateur si les données sont bonnes ou pas
            $confirmQuestion = new ConfirmationQuestion("Is it correct ? (y/N)\n", false);
            // Si les données ne sont pas bonnes, on recommence la boucle
            // On supprime les arguments, pour poser la question à l'utilisateur
            // On détruit l'objet Clade
            if (!$helper->ask($input, $output, $confirmQuestion)) {
                $confirm = false;
                $input->setArgument('name', null);
                $input->setArgument('description', null);
                unset($clade);
            } else {
                $confirm = true;
            }
        } while (!$confirm);

        // On persiste l'objet
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($clade);
        $em->flush();

        $output->writeln('<info>The clade was successfully added</info>');
    }
}
