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
        $output->writeln('<info>Add a clade</info>');
        $helper = $this->getHelper('question');

        $clade = new Clade();

        do
        {
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


            // Resume
            $userData = "Clade name:\t" . $clade->getName() . "\n";
            $userData .= ($clade->getMainClade()) ? "Main clade:\tYes\n" : "Main clade:\tNo\n";
            $userData .= "Description:\n" . $clade->getDescription() . "\n";

            $output->writeln("\n".$userData);

            // User Confirmation before flush it
            $confirmQuestion = new ConfirmationQuestion('Is it correct ? (y/N)', false);
            if (!$helper->ask($input, $output, $confirmQuestion)) {
                $confirm = false;
                $input->setArgument('name', null);
                $input->setArgument('description', null);
            } else {
                $confirm = true;
            }
        } while (!$confirm);

        // Persist and flush
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($clade);
        $em->flush();

        $output->writeln('<info>The clade was successfully added</info>');
    }
}
