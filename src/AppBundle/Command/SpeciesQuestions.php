<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class SpeciesQuestions
{
    private $species;
    private $cladeList;
    private $input;

    public function __construct($input, $cladeList, $species = null)
    {
        $this->species = $species;
        $this->input = $input;
        $this->cladeList = $cladeList;
    }

    public function getCladeQuestion()
    {
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

        return $question;
    }

    public function getScientificNameQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter the scientific name of the species (actual: '.$this->species->getScientificName().'): ', $this->species->getScientificName());
        } else {
            $question = new Question('Please enter the scientific name of the species: ');
        }

        $question->setValidator(function ($answer) {
            if (!preg_match('#^[A-Z][a-z]* [a-z]*$#', $answer)) { // Or is_int()
                throw new \RuntimeException(
                    'The scientific name have not the goot pattern ! (eg: "Candida albicans")'
                );
            }

            return $answer;
        });

        return $question;
    }

    public function getLineageQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter lineages of the species (use "; " as separator) (actual: '.implode('; ', $this->species->getLineages()).'): ', implode('; ', $this->species->getLineages()));
        } else {
            $question = new Question('Please enter lineages of the species (use "; " as separator): ');
        }

        // On crée un validateur, qui vérifié que le la liste est correctement formatée
        $question->setValidator(function ($answer) {
            if (!preg_match('#^([a-zA-Z0-9 ]*; )*[a-zA-Z0-9 ]*[^; ]$#', $answer)) {
                throw new \RuntimeException(
                    'The list have not the goot pattern ! (eg: "lineage 1; lineage 2; lineage 3; [...]; last lineage")'
                );
            }

            return explode('; ', $answer);
        });

        return $question;
    }

    public function getGeneticCodeQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter the genetic code of the species (actual: '.$this->species->getGeneticCode().'): ', $this->species->getGeneticCode());
        } else {
            $question = new Question('Please enter the genetic code of the species (default: 1): ', 1);
        }

        $question->setValidator(function ($answer) {
            if (0 === (int) $answer) {
                throw new \RuntimeException(
                    'The mito code may be an integer.'
                );
            }

            return $answer;
        });

        return $question;
    }

    public function getMitoCodeQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter the mito code of the species (actual: '.$this->species->getMitoCode().'): ', $this->species->getMitoCode());
        } else {
            $question = new Question('Please enter the mito code of the species (default: 3): ', 3);
        }

        $question->setValidator(function ($answer) {
            if (0 === (int) $answer) {
                throw new \RuntimeException(
                    'The mito code may be an integer.'
                );
            }

            return $answer;
        });

        return $question;
    }

    public function getTaxIdQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter the taxid of the species (actual: '.$this->species->getTaxid().'): ', $this->species->getTaxid());
        } else {
            $question = new Question('Please enter the taxid of the species: ');
        }

        $question->setValidator(function ($answer) {
            if (0 === (int) $answer) {
                throw new \RuntimeException(
                    'The taxid may be an integer.'
                );
            }

            return $answer;
        });

        return $question;
    }

    public function getSynonymesQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter synonymes of the species (use "; " as separator)(actual: '.implode('; ', $this->species->getSynonymes()).'): ', implode('; ', $this->species->getSynonymes()));
        } else {
            $question = new Question('Please enter synonymes of the species (use "; " as separator): ');
        }

        // On crée un validateur, qui vérifié que le la liste est correctement formatée
        $question->setValidator(function ($answer) {
            if (!preg_match('#^([a-zA-Z0-9 ]*; )*[a-zA-Z0-9 ]*[^; ]$|^\s*$#', $answer)) {
                throw new \RuntimeException(
                    'The list have not the goot pattern ! (eg: "synonyme 1; synonyme 2; synonyme 3; [...]; last synonyme")'
                );
            }

            return explode('; ', $answer);
        });

        return $question;
    }

    public function getDescriptionQuestion()
    {
        if ($this->speciesExists()) {
            $question = new Question('Please enter the description of the species (actual: '.$this->species->getDescription().'): ', $this->species->getDescription());
        } else {
            $question = new Question('Please enter the description of the species: ');
        }

        return $question;
    }

    public function getSummary($taxid)
    {
        $summary = array(
            '',
            'Summary:',
            'Clade: '.$this->input->getArgument('clade')->getName(),
            'Scientific name: '.$this->input->getArgument('scientific-name'),
            'Genus: '.$this->input->getArgument('genus'),
            'Species: '.$this->input->getArgument('species'),
            'Lineages: '.implode('; ', $this->input->getArgument('lineages')),
            'Genetic Code: '.$this->input->getArgument('genetic-code'),
            'Mito Code: '.$this->input->getArgument('mito-code'),
            'Synonymes: '.implode('; ', $this->input->getArgument('synonymes')),
            'Description: '.$this->input->getArgument('description'),
            'TaxId: '.$taxid,
        );

        return $summary;
    }

    public function getConfirmationQuestion()
    {
        return new ConfirmationQuestion('<question>Is it correct ? (y/N)</question> ', false);
    }

    private function speciesExists()
    {
        if (null !== $this->species) {
            return true;
        } else {
            return false;
        }
    }

    public function ask(array $questions)
    {
        if (!$this->input->getArgument('scientific-name')) {
            $questions['scientific-name'] = $this->getScientificNameQuestion();
        }

        if (!$this->input->getArgument('lineages')) {
            $questions['lineages'] = $this->getLineageQuestion();
        }

        if (!$this->input->getArgument('genetic-code')) {
            $questions['genetic-code'] = $this->getGeneticCodeQuestion();
        }

        if (!$this->input->getArgument('mito-code')) {
            $questions['mito-code'] = $this->getMitoCodeQuestion();
        }

        if (!$this->input->getArgument('synonymes')) {
            $questions['synonymes'] = $this->getSynonymesQuestion();
        }

        if (!$this->input->getArgument('description')) {
            $questions['description'] = $this->getDescriptionQuestion();
        }

        return $questions;
    }
}
