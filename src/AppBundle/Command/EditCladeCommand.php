<?php
// src/AppBundle/Command/EditCladeCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Clade;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class EditCladeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bio:clade:edit')
            ->setDescription('Edit a clade')
            ->addArgument(
                'cladeId',
                InputArgument::OPTIONAL,
                'Id of the clade'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Si l'utilisateur ne donne pas d'ID lors de la demande d'édition du clade, on lui affiche la liste des clades
        // et il choisi le clade qu'il souhaite modifier
        if (!$input->getArgument('cladeId')) {
            // Appeller la commande bio:clade:list, pour donner à l'utilisateur une liste des clades
            $listCladeCommand = $this->getApplication()->find('bio:clade:list');
            $listCladeCommandInput = new ArrayInput(array('command' => 'bio:clade:list'));
            $listCladeCommand->run($listCladeCommandInput, $output);

            // On demande à l'utilisateur quel clade il veut modifier
            $cladeIdQuestion = new Question("\nPlease enter the ID of the clade:\n");
            // On récupère le clade
            $input->setArgument('cladeId', $helper->ask($input, $output, $cladeIdQuestion));
        }

        $clade = $em->getRepository('AppBundle:Clade')->findOneById($input->getArgument('cladeId'));

        do
        {
            // On propose à l'utilisateur de modifier les champs

            // CladeName
            $cladeNameQuestion = new Question("\nPlease enter the name of the clade: (actual value: " . $clade->getName() . ")\n", $clade->getName());
            $cladeName = $helper->ask($input, $output, $cladeNameQuestion);

            // CladeDescription
            $cladeDescriptionQuestion = new Question("\nPlease enter the description of the clade: (actual value: " . $clade->getDescription() . ")\n", $clade->getDescription());
            $cladeDescription = $helper->ask($input, $output, $cladeDescriptionQuestion);

            // MainClade
            $isMainCladeQuestion = new ConfirmationQuestion("\nIs it a Main Clade ? (y/n) (actual value: " . $clade->getMainClade() . ")\n", $clade->getMainClade());
            $cladeMainClade = $helper->ask($input, $output, $isMainCladeQuestion);

            // On fait un résumé des données récupérées
            $userData = "Clade name:\t" . $cladeName . "\n";
            $userData .= ($cladeMainClade) ? "Main clade:\tYes\n" : "Main clade:\tNo\n";
            $userData .= "Description:\n" . $cladeDescription . "\n";

            $output->writeln("\n".$userData);

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
        $clade->setName($cladeName);
        $clade->setDescription($cladeDescription);
        $clade->setMainClade($cladeMainClade);

        // On persiste l'objet
        $em->flush();

        $output->writeln('<info>The clade was successfully edited</info>');
    }
}
