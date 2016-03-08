<?php
// src/AppBundle/Command/EditSpeciesCommand.php

namespace Grycii\AppBundle\Command;

use Grycii\AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class EditSpeciesCommand extends ContainerAwareCommand
{
    /**
     * @var array Clade
     */
    private $cladeList = array();

    /**
     * @var array Species
     */
    private $speciesList = array();

    /**
     * @var Species
     */
    private $species;

    protected function configure()
    {
        $this
            ->setName('bio:species:edit')
            ->setDescription('Add a species')
            ->setDefinition(array(
                new InputArgument('scientific-name', InputArgument::REQUIRED, 'The scientific name of the species'),
                new InputArgument('genus', InputArgument::REQUIRED, 'The genus of the species'),
                new InputArgument('species', InputArgument::REQUIRED, 'The name of the species'),
                new InputArgument('clade', InputArgument::REQUIRED, 'Name of the clade'),
                new InputArgument('lineages', InputArgument::REQUIRED, 'The lineages of the species'),
                new InputArgument('genetic-code', InputArgument::REQUIRED, 'The genetic code of the species'),
                new InputArgument('mito-code', InputArgument::REQUIRED, 'The mito code of the species'),
                new InputArgument('description', InputArgument::REQUIRED, 'The description of the species'),
                new InputArgument('taxid', InputArgument::OPTIONAL, 'TaxId of the species'),
                new InputArgument('synonymes', InputArgument::OPTIONAL, 'Synonymes of the species'),
            ))
            ->setHelp(<<<EOT
The <info>bio:species:add</info> command creates a species:
  <info>bin/console bio:species:add</info>
This interactive shell will ask you all informations on the species and on witch clade you want link the species.
You can alternatively specify the clade as argument:
  <info>bin/console bio:species:add Yarrowia</info>
You can use an autofill option via the taxid flag:
  <info>bin/console bio:species:add --taxid 4952</info>
EOT
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $em = $this->getContainer()->get('doctrine')->getManager();

        // Retrieve all clades stocked in the database
        $clades = $em->getRepository('AppBundle:Clade')->findAll();
        foreach ($clades as $clade) {
            $this->cladeList[$clade->getName()] = $clade;
        }

        // Idem, but for the species
        $species = $em->getRepository('AppBundle:Species')->findAll();
        foreach ($species as $specy) {
            $this->speciesList[$specy->getScientificName()] = $specy;
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        do {
            $questions = array();

            // Appeller la commande bio:species:list, pour donner à l'utilisateur une liste des species
            $listSpeciesCommand = $this->getApplication()->find('bio:species:list');
            $listSpeciesCommandInput = new ArrayInput(array('command' => 'bio:species:list'));
            $listSpeciesCommand->run($listSpeciesCommandInput, $output);

            $question = new Question('Please enter the name of the species: ');
            $question->setAutocompleterValues(array_keys($this->speciesList));
            // On crée un validateur, qui vérifié que le nom entré par l'utilisateur est bien un choix possible
            $question->setValidator(function ($answer) {
                if (!in_array($answer, array_keys($this->speciesList))) {
                    throw new \RuntimeException(
                        'The species doesn\'t exist !'
                    );
                }

                return $this->speciesList[$answer];
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $this->species = $answer;

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

                $questions['clade'] = $question;
            }

            if (!$input->getArgument('scientific-name')) {
                $question = new Question('Please enter the scientific name of the species (actual: '.$this->species->getScientificName().'): ', $this->species->getScientificName());
                $question->setValidator(function ($answer) {
                    if (!preg_match('#^[A-Z][a-z]* [a-z]*$#', $answer)) { // Or is_int()
                        throw new \RuntimeException(
                            'The scientific name have not the goot pattern ! (eg: "Candida albicans")'
                        );
                    }

                    return $answer;
                });
                $questions['scientific-name'] = $question;
            }

            if (!$input->getArgument('lineages')) {
                $question = new Question('Please enter lineages of the species (use "; " as separator) (actual: '.implode('; ', $this->species->getLineages()).'): ', implode('; ', $this->species->getLineages()));
                // On crée un validateur, qui vérifié que le la liste est correctement formatée
                $question->setValidator(function ($answer) {
                    if (!preg_match('#^([a-zA-Z0-9 ]*; )*[a-zA-Z0-9 ]*[^; ]$#', $answer)) {
                        throw new \RuntimeException(
                            'The list have not the goot pattern ! (eg: "lineage 1; lineage 2; lineage 3; [...]; last lineage")'
                        );
                    }

                    return explode('; ', $answer);
                });
                $questions['lineages'] = $question;
            }

            if (!$input->getArgument('genetic-code')) {
                $question = new Question('Please enter the genetic code of the species (actual: '.$this->species->getGeneticCode().'): ', $this->species->getGeneticCode());
                $question->setValidator(function ($answer) {
                    if (0 === (int) $answer) {
                        throw new \RuntimeException(
                            'The mito code may be an integer.'
                        );
                    }

                    return $answer;
                });
                $questions['genetic-code'] = $question;
            }

            if (!$input->getArgument('mito-code')) {
                $question = new Question('Please enter the mito code of the species (actual: '.$this->species->getMitoCode().'): ', $this->species->getMitoCode());
                $question->setValidator(function ($answer) {
                    if (0 === (int) $answer) {
                        throw new \RuntimeException(
                            'The mito code may be an integer.'
                        );
                    }

                    return $answer;
                });
                $questions['mito-code'] = $question;
            }

            if (!$input->getArgument('taxid')) {
                $question = new Question('Please enter the taxid of the species (actual: '.$this->species->getTaxid().'): ', $this->species->getTaxid());
                $question->setValidator(function ($answer) {
                    if (0 === (int) $answer) {
                        throw new \RuntimeException(
                            'The taxid may be an integer.'
                        );
                    }

                    return $answer;
                });
                $questions['taxid'] = $question;
            }

            if (!$input->getArgument('synonymes')) {
                $question = new Question('Please enter synonymes of the species (use "; " as separator)(actual: '.implode('; ', $this->species->getSynonymes()).'): ', implode('; ', $this->species->getSynonymes()));
                // On crée un validateur, qui vérifié que le la liste est correctement formatée
                $question->setValidator(function ($answer) {
                    if (!preg_match('#^([a-zA-Z0-9 ]*; )*[a-zA-Z0-9 ]*[^; ]$|^\s*$#', $answer)) {
                        throw new \RuntimeException(
                            'The list have not the goot pattern ! (eg: "synonyme 1; synonyme 2; synonyme 3; [...]; last synonyme")'
                        );
                    }

                    return explode('; ', $answer);
                });
                $questions['synonymes'] = $question;
            }

            if (!$input->getArgument('description')) {
                $question = new Question('Please enter the description of the species (actual: '.$this->species->getDescription().'): ', $this->species->getDescription());
                $questions['description'] = $question;
            }

            foreach ($questions as $name => $question) {
                $answer = $this->getHelper('question')->ask($input, $output, $question);
                $input->setArgument($name, $answer);
            }

            // On utilise le nom scientifique pour déduire le genre et l'espèce
            $scientificNameExploded = explode(' ', $input->getArgument('scientific-name'));
            $input->setArgument('genus', $scientificNameExploded[0]);
            $input->setArgument('species', $scientificNameExploded[1]);

            $output->writeln(array(
                '',
                'Summary:',
                'Clade: '.$input->getArgument('clade')->getName(),
                'Scientific name: '.$input->getArgument('scientific-name'),
                'Genus: '.$input->getArgument('genus'),
                'Species: '.$input->getArgument('species'),
                'Lineages: '.implode('; ', $input->getArgument('lineages')),
                'Genetic Code: '.$input->getArgument('genetic-code'),
                'Mito Code: '.$input->getArgument('mito-code'),
                'Synonymes: '.implode('; ', $input->getArgument('synonymes')),
                'Description: '.$input->getArgument('description'),
                'TaxId: '.$input->getArgument('taxid'),
            ));

            $confirmQuestion = new ConfirmationQuestion('<question>Is it correct ? (y/N)</question> ', false);

            if (!$this->getHelper('question')->ask($input, $output, $confirmQuestion)) {
                $input->setArgument('clade', null);
                $input->setArgument('scientific-name', null);
                $input->setArgument('genus', null);
                $input->setArgument('species', null);
                $input->setArgument('lineages', null);
                $input->setArgument('genetic-code', null);
                $input->setArgument('mito-code', null);
                $input->setArgument('synonymes', null);
                $input->setArgument('description', null);
                $input->setArgument('taxid', null);
                $confirm = false;
            } else {
                $confirm = true;
            }
        } while (!$confirm);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $species = $this->species;
        $species->setClade($input->getArgument('clade'));
        $species->setScientificName($input->getArgument('scientific-name'));
        $species->setGenus($input->getArgument('genus'));
        $species->setSpecies($input->getArgument('species'));
        $species->setLineages($input->getArgument('lineages'));
        $species->setGeneticCode($input->getArgument('genetic-code'));
        $species->setMitoCode($input->getArgument('mito-code'));
        $species->setSynonymes($input->getArgument('synonymes'));
        $species->setDescription($input->getArgument('description'));
        $species->setTaxid($input->getArgument('taxid'));

        // On persiste l'objet
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($species);
        $em->flush();

        $output->writeln('<info>The species was successfully added</info>');
    }
}
