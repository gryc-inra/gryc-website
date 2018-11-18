<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class DeleteStrainCommand extends ContainerAwareCommand
{
    private $entityManager;
    private $strain;
    private $strainList;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('gryc:strain:delete')
            ->setDescription('Delete strain');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $strainList = $this->entityManager->getRepository('App:Strain')->findAll();
        if (empty($strainList)) {
            throw new \RuntimeException(
                '<error>No strains in the database !</error>'
            );
        }

        foreach ($strainList as $strain) {
            $this->strainList[$strain->getName()] = $strain;
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter the name of the strain: ');
        $question->setAutocompleterValues(array_keys($this->strainList));
        // Verify that the name of the species is an existing species, if yes return the species object
        $question->setValidator(function ($answer) {
            if (!\in_array($answer, array_keys($this->strainList), true)) {
                throw new \RuntimeException(
                    'The strain doesn\'t exist !'
                );
            }

            return $this->strainList[$answer];
        });

        $this->strain = $this->getHelper('question')->ask($input, $output, $question);

        $confirmQuestion = new ConfirmationQuestion('<question>Do you confirm the deletion ? (y/N)</question> ', false);
        if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
            throw new \RuntimeException(
                'Deletion aborted !'
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>The deletion of the strain '.$this->strain->getName().' start...</comment>');

        $this->entityManager->remove($this->strain);
        $this->entityManager->flush();

        $output->writeln('<info>The strain has been successfully deleted !</info>');
    }
}
