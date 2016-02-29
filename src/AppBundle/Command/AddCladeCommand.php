<?php
// src/AppBundle/Command/AddCladeCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Clade;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AddCladeCommand extends ContainerAwareCommand
{
    // This attribute contains all the clades persisted in the database
    /**
     * @var array Clade
     */
    private $clades = array();

    protected function configure()
    {
        $this
            ->setName('bio:clade:add')
            ->setAliases(array('bio:add:clade'))
            ->setDescription('Add a clade')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'The name of the clade'),
                new InputArgument('description', InputArgument::REQUIRED, 'The description of the clade'),
                new InputOption('main-clade', false, InputOption::VALUE_NONE, 'Set the clade as a main clade'),
            ))
            ->setHelp(<<<EOT
The <info>bio:clade:add</info> command creates a clade:
  <info>bin/console bio:clade:add Candida</info>
This interactive shell will ask you for a description.
You can alternatively specify the description as the second argument:
  <info>bin/console bio:clade:add Candida "Candida, a so beautiful clade."</info>
You can create a main clade via the main-clade flag:
  <info>bin/console bio:clade:add Candida --main-clade</info>

EOT
            );
    }

    // Here, prepare some variables used later and some control on the command line argument
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // Retrieve the list of all clades, and put it in a array with the clade's name as key and the clade's object as value
        $clades = $this->getContainer()->get('doctrine')->getManager()->getRepository('AppBundle:Clade')->findAll();
        foreach ($clades as $clade) {
            $this->clades[$clade->getName()] = $clade;
        }

        // If the user give a name to the new clade in the command line, verify the 'pattern' of the name and that no clade with the same name doesn't already exists
        if ($answer = $input->getArgument('name')) {
            if (!preg_match('#[A-Z][a-z]*$#', $answer)) {
                throw new \RuntimeException(
                    'The name have not the goot pattern ! (eg: "Candida")'
                );
            }

            if (array_key_exists($answer, $this->clades)) {
                throw new \RuntimeException(
                    'This clade already exists !'
                );
            }
        }
    }

    // Here we prepare the question to the user, if he doesn't give all the arguments in the command line
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        do {
            $questions = array();

            if (!$input->getArgument('name')) {
                $question = new Question('Please enter the name of the clade: ');
                // In this validator: control the 'pattern' and that no clade with this name doesn't already exists
                $question->setValidator(function ($answer) {
                    if (!preg_match('#[A-Z][a-z]*$#', $answer)) {
                        throw new \RuntimeException(
                            'The name have not the goot pattern ! (eg: "Candida")'
                        );
                    }

                    if (array_key_exists($answer, $this->clades)) {
                        throw new \RuntimeException(
                            'This clade already exist.'
                        );
                    }

                    return $answer;
                });
                $questions['name'] = $question;
            }

            if (!$input->getArgument('description')) {
                $question = new Question('Please enter the description of the clade: ');
                // The description can't be empty
                $question->setValidator(function ($answer) {
                    if (empty($answer)) {
                        throw new \RuntimeException(
                            'The description can\'t be empty !'
                        );
                    }

                    return $answer;
                });
                $questions['description'] = $question;
            }

            // We do a loop on on the questions, and ask it to the user
            foreach ($questions as $name => $question) {
                $answer = $this->getHelper('question')->ask($input, $output, $question);
                $input->setArgument($name, $answer);
            }

            // Ask to the user if he is sure of his answers.
            $output->writeln(array(
                '',
                'Summary:',
                'Name: '.$input->getArgument('name'),
                'Description: '.$input->getArgument('description'),
            ));
            $output->writeln('Main clade: '.(($input->getOption('main-clade')) ? 'Yes' : 'No'));

            $confirmQuestion = new ConfirmationQuestion('<question>Is it correct ? (y/N)</question> ', false);

            if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
                $confirm = false;
                $input->setArgument('name', null);
                $input->setArgument('description', null);
            } else {
                $confirm = true;
            }
        } while (!$confirm);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create the Clade object and set all the arguments
        $clade = new Clade();
        $clade->setName($input->getArgument('name'));
        $clade->setDescription($input->getArgument('description'));
        $clade->setMainClade($input->getOption('main-clade'));

        // Persist and flush the object in the database
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($clade);
        $em->flush();

        $output->writeln('<info>The clade was successfully added.</info>');
    }
}
