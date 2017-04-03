<?php

// src/AppBundle/Controller/SpeciesController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Form\Type\SpeciesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Species administration controller.
 */
class SpeciesAdminController extends Controller
{
    /**
     * A constant that contain the api url.
     */
    const NCBI_TAXONOMY_API_LINK = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&id=';

    /**
     * List all the species in the admin section.
     *
     * @Route("/admin/species", name="species_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getAllSpeciesAndStrains();

        return $this->render('species/admin/index.html.twig', [
            'speciesList' => $species,
        ]);
    }

    /**
     * Add a species.
     *
     * @Route("/admin/species/add", name="species_add")
     */
    public function addAction(Request $request)
    {
        $species = new Species();
        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($species);
            $em->flush();

            $this->addFlash('success', 'The species was successfully added.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a species.
     *
     * @Route("/admin/species/{id}/edit", name="species_edit")
     */
    public function editAction(Request $request, Species $species)
    {
        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The species was successfully edited.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/edit.html.twig', [
            'species' => $species,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a species.
     *
     * @Route("/admin/species/{id}/delete", name="species_delete")
     */
    public function deleteAction(Request $request, Species $species)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, [
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($species);
            $em->flush();

            $this->addFlash('success', 'The species was successfully deleted.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/delete.html.twig', [
            'species' => $species,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Consult the ncbi taxonomy api, and return a json with the interesting data.
     * Used in AddSpecies for the autocomplete method.
     *
     * @Route("/admin/species/json/{taxid}", name="species_getjson", condition="request.isXmlHttpRequest()")
     */
    public function getJsonAction($taxid)
    {
        // Retrieve the page content (xml code)
        $xmlString = file_get_contents(self::NCBI_TAXONOMY_API_LINK.$taxid);

        // Create a crawler and give the xml code to it
        $crawler = new Crawler($xmlString);

        // Initialise the response
        $response = [];

        // Count the number of taxon tag, if different of 0 there are contents, else the document is empty, it's because the Taxon Id doesn't exists
        if (0 !== $crawler->filterXPath('//TaxaSet/Taxon')->count()) {
            // If the tag Rank contain 'species', the Id match on a species, else, it's not correct.
            if ('species' === $crawler->filterXPath('//TaxaSet/Taxon/Rank')->text()) {
                // Use the crawler to crawl the document and fill the response
                $response['scientificName'] = $crawler->filterXPath('//TaxaSet/Taxon/ScientificName')->text();

                // Explode the scientific name to retrieve: genus and species
                $scientificNameExploded = explode(' ', $response['scientificName']);
                $response['genus'] = $scientificNameExploded[0];
                $response['species'] = $scientificNameExploded[1];

                $response['geneticCode'] = $crawler->filterXPath('//TaxaSet/Taxon/GeneticCode/GCId')->text();
                $response['mitoCode'] = $crawler->filterXPath('//TaxaSet/Taxon/MitoGeneticCode/MGCId')->text();
                $response['lineages'] = explode('; ', $crawler->filterXPath('//TaxaSet/Taxon/Lineage')->text());

                // He re count the number of synonym tag, if the count is different to 0, there are synonymes
                if (0 !== $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->count()) {
                    // Use a closure on the tag Synonym to extract all synonymes and fill an array
                    $synonymes = $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->each(function (Crawler $node) {
                        return $node->text();
                    });
                    $response['synonymes'] = $synonymes;
                }
            } else {
                $response['error'] = 'This ID does not match on a species';
            }
        } else {
            $response['error'] = 'This ID does not exists';
        }

        return new JsonResponse($response);
    }
}
