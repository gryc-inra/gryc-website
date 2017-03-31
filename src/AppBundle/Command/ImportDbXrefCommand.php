<?php

// src/AppBundle/Command/ImportDbXrefCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Dbxref;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImportDbXrefCommand extends ContainerAwareCommand
{
    const NCBI_DB_XREF_LIST = 'http://www.ncbi.nlm.nih.gov/genbank/collab/db_xref';

    protected function configure()
    {
        $this
            ->setName('bio:dbxref:import')
            ->setDescription('Import DbXref data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // DbXref persistés
        $persistDbxref = [];

        // On initialise le Crawler
        $crawler = new Crawler();
        $crawler->addHtmlContent(file_get_contents(self::NCBI_DB_XREF_LIST));

        // On compte le nombre de <tr> présent dans le tableau qui nous intéresse
        $nbRows = $crawler->filter('#maincontent > .col1 > table > tbody > tr')->count();

        // On fait une boucle sur le nombre de lignes du tableau
        for ($i = 0; $i < $nbRows; ++$i) {
            // Si il y a un <td> dans le <tr> (on compte le nombre d'enfants dans le <tr>, si il y en a plus que 0, alors il y a des <td>)
            if (0 !== $crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->count()) {
                // Si il y a du contenu dans le <td> (on extrait le texte présent sur le premier élément de l'enfant du <tr>, donc si il y a du texte dans le premier <td>)
                if (!empty(trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(0)->text(), chr(0xC2).chr(0xA0).chr(0x0A)))) {
                    // On crée un nouvel objet
                    $dbxref = new Dbxref();

                    // On récupère le nom de l'entrée
                    $dbxref->setName(trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(0)->text()));

                    if (null === $em->getRepository('AppBundle:Dbxref')->findOneByName($dbxref->getName())) {
                        // Si il un lien dans le premier <td>, on défini l'url
                        if (0 !== $crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(0)->filter('a')->count()) {
                            $url = trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(0)->filter('a')->attr('href'));
                            if (!preg_match('#^http://#', $url)) {
                                $url = 'http://www.ncbi.nlm.nih.gov'.$url;
                            }
                            $dbxref->setUrl($url);
                        } else {
                            $dbxref->setUrl('none');
                        }

                        // On défini la description
                        $dbxref->setDescription(trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(2)->text()));

                        // Si il y a plus d'un /db_xref, on récupère le premier
                        if (1 < $crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(4)->filter('p')->count()) {
                            $pattern = trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(4)->filter('p')->eq(0)->text());
                        } else {
                            $pattern = trim($crawler->filter('#maincontent > .col1 > table > tbody > tr')->eq($i)->children()->eq(4)->text());
                        }

                        // On fait un split sur le /db_xref, et on récupère donc la valeur non splitté, cette dernière se trouve en 2ème position
                        $dbxref->setPattern(trim(preg_split('#^(/)?db_xref=(")?|:.*(")?|"#', $pattern)[1]));

                        // On défini la source, ici le script récupère les données sur le NCBI
                        $dbxref->setSource('ncbi');

                        // On persiste l'objet
                        $em->persist($dbxref);

                        // On inrémente de 1 le nombre de DbXref persistés
                        $persistDbxref[] = $dbxref->getName();
                    }
                }
            }
        }
        // On flush les données persistés
        $em->flush();

        // On affiche un message de réussite

        // Si on a persisté des Dbxref
        if (0 !== $nbPersistDbxref = count($persistDbxref)) {
            // On indique combien ont été persistés
            $output->writeln('<info>'.$nbPersistDbxref.(($nbPersistDbxref > 1) ? ' were' : ' was').' added to the database !</info>');

            // On initialise une liste des Dbxref persistés
            $persistDbxrefList = '';
            foreach ($persistDbxref as $dbxref) {
                // Si il s'agit de la dernière ittération
                if ($dbxref === end($persistDbxref)) {
                    $persistDbxrefList .= $dbxref.'.';
                } else {
                    $persistDbxrefList .= $dbxref.', ';
                }
            }
            $output->writeln('<info>List: '.$persistDbxrefList.'</info>');
        } else {
            $output->writeln('<info>No data added to the database !</info>');
        }
    }
}
