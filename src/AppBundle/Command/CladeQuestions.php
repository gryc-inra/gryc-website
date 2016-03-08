<?php

namespace Grycii\AppBundle\Command;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CladeQuestions
{
    private $clade;
    private $cladeList;
    private $input;

    public function __construct($input, $cladeList, $clade = null)
    {
        $this->clade = $clade;
        $this->cladeList = $cladeList;
        $this->input = $input;
    }

    public function getNameQuestion()
    {
        if ($this->cladeExists()) {
            $question = new Question('Please enter the name of the clade (actual value: '.$this->clade->getName().'): ', $this->clade->getName());
        } else {
            $question = new Question('Please enter the name of the clade: ');
        }
        $question->setValidator(function ($answer) {
            if (!preg_match('#[A-Z][a-z]*$#', $answer)) {
                throw new \RuntimeException(
                    'The name have not the goot pattern ! (eg: "Candida")'
                );
            }

            if (array_key_exists($answer, $this->cladeList)) {
                if ($this->cladeExists()) {
                    if ($answer !== $this->input->getArgument('clade')->getName()) {
                        throw new \RuntimeException(
                            'This clade already exists !'
                        );
                    }
                } else {
                    throw new \RuntimeException(
                        'This clade already exists !'
                    );
                }
            }

            return $answer;
        });

        return $question;
    }

    public function getDescriptionQuestion()
    {
        if ($this->cladeExists()) {
            $question = new Question('Please enter the description of the clade (actual value: '.$this->clade->getDescription().'): ', $this->clade->getDescription());
        } else {
            $question = new Question('Please enter the description of the clade: ');
        }
        // Just verify the description isn't empty
        $question->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new \RuntimeException(
                    'The description can\'t be empty !'
                );
            }

            return $answer;
        });

        return $question;
    }

    public function getSummary()
    {
        $summary = array(
            '',
            'Summary:',
            'Name: '.$this->input->getArgument('name'),
            'Description: '.$this->input->getArgument('description'),
        );
        $summary[] = ('Main clade: '.(($this->input->getOption('main-clade')) ? 'Yes' : 'No'));

        return $summary;
    }

    public function getConfirmationQuestion()
    {
        return new ConfirmationQuestion('<question>Is it correct ? (y/N)</question> ', false);
    }

    private function cladeExists()
    {
        if (null !== $this->clade) {
            return true;
        } else {
            return false;
        }
    }

    public function ask($questions)
    {
        if (!$this->input->getArgument('name')) {
            $questions['name'] = $this->getNameQuestion();
        }

        if (!$this->input->getArgument('description')) {
            $questions['description'] = $this->getDescriptionQuestion();
        }

        return $questions;
    }
}
