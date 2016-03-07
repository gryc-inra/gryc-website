<?php
// src/AppBundle/Command/EditCladeCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Clade;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class EditCladeCommand extends ContainerAwareCommand
{
    // Those attributes contain all the clade in the database and the clade before edition
    /**
     * @var array Clade
     */
    private $cladeList;

    /**
     * @var Clade
     */
    private $clade;

    protected function configure()
    {
        $this
            ->setName('bio:clade:edit')
            ->setAliases(array('bio:edit:clade'))
            ->setDescription('Edit a clade')
            ->setDefinition(array(
                new InputArgument('clade', InputArgument::REQUIRED, 'Id of the clade'),
                new InputArgument('name', InputArgument::REQUIRED, 'The name of the clade'),
                new InputArgument('description', InputArgument::REQUIRED, 'The description of the clade'),
                new InputOption('main-clade', false, InputOption::VALUE_NONE, 'Set the clade as a main clade'),
            ))
            ->setHelp(<<<EOT
The <info>bio:clade:edit</info> command edit a clade:
  <info>bin/console bio:clade:edit Candida</info>
This interactive shell will ask you for a name and description.
You can alternatively specify the name and description as the second and third arguments:
  <info>bin/console bio:clade:edit Candida CandidaII "Candida, a so beautiful clade."</info>
You can create a main clade via the main-clade flag:
  <info>bin/console bio:clade:edit Candida --main-clade</info>

EOT
            );
    }

    // Here, prepare some variables used later and some control on the command line argument
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // Retrieve all clades persisted in the database and put it in the cladeList attribute
        $clades = $this->getContainer()->get('doctrine')->getManager()->getRepository('AppBundle:Clade')->findAll();
        foreach ($clades as $clade) {
            $this->cladeList[$clade->getName()] = $clade;
        }

        // If the user give wich clade he wants edits, we control that Clade exists, and if it exists put it in the argument
        if ($input->getArgument('clade')) {
            if (!in_array($input->getArgument('clade'), array_keys($this->cladeList))) {
                throw new \RuntimeException(
                    'The clade doesn\'t exist !'
                );
            }
            $input->setArgument('clade', $this->cladeList[$input->getArgument('clade')]);
        }

        // If the user give a new name in the command line, control that name doesn't already exist
        if (array_key_exists($input->getArgument('name'), $this->cladeList) && $input->getArgument('name') !== $input->getArgument('clade')->getName()) {
            throw new \RuntimeException(
                'This clade already exists !'
            );
        }
    }

    // Here we prepare the question to the user, if he doesn't give all the arguments in the command line
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        do {
            $questions = array();

            // If the user doesn't give the clade he wants edit, we ask him
            if (!$input->getArgument('clade')) {
                // List all the persisted clades, call the bio:clade:list command to do it
                $listCladeCommand = $this->getApplication()->find('bio:clade:list');
                $listCladeCommandInput = new ArrayInput(array('command' => 'bio:clade:list'));
                $listCladeCommand->run($listCladeCommandInput, $output);

                $question = new Question('Enter the name of the clade: ');
                $question->setAutocompleterValues(array_keys($this->cladeList));
                // Verify that the name of the clade is an existing clade, if yes return the clade object
                $question->setValidator(function ($answer) {
                    if (!in_array($answer, array_keys($this->cladeList))) {
                        throw new \RuntimeException(
                            'The clade doesn\'t exist !'
                        );
                    }

                    return $this->cladeList[$answer];
                });

                // We need to ask this question before ask other questions, because we use the data of the editing clade in the questions
                $answer = $this->getHelper('question')->ask($input, $output, $question);
                $input->setArgument('clade', $answer);
            }

            // Definy the clade attribut: Here we are sure to can it:
            // - if the user give it in the command line, we have set the attribut in the initialize method
            // - if the user doesn't give the argument in the command line, we have ask him which clade he wants editing previously
            $this->clade = $input->getArgument('clade');

            if (!$input->getArgument('name')) {
                $question = new Question("\nPlease enter the name of the clade: (actual value: ".$this->clade->getName().")\n", $this->clade->getName());
                // Control that name have a good pattern and doesn't already exist
                $question->setValidator(function ($answer) use ($input) {
                    if (!preg_match('#[A-Z][a-z]*$#', $answer)) {
                        throw new \RuntimeException(
                            'The name have not the goot pattern ! (eg: "Candida")'
                        );
                    }

                    if (array_key_exists($answer, $this->cladeList) && $answer !== $input->getArgument('clade')->getName()) {
                        throw new \RuntimeException(
                            'This clade already exists !'
                        );
                    }

                    return $answer;
                });
                $questions['name'] = $question;
            }

            if (!$input->getArgument('description')) {
                $question = new Question("\nPlease enter the description of the clade: (actual value: ".$this->clade->getDescription().")\n", $this->clade->getDescription());
                // Just verify the description isn't empty
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

            // Loop on the questions
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
        // Edit the clade object
        $this->clade->setName($input->getArgument('name'));
        $this->clade->setDescription($input->getArgument('description'));
        $this->clade->setMainClade($input->getOption('main-clade'));

        // Persist it
        $this->getContainer()->get('doctrine')->getManager()->flush();

        $output->writeln('<info>The clade was successfully edited</info>');
    }
}
