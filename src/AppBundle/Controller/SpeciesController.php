<?php
// src/AppBundle/Controller/SpeciesController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Form\Type\SpeciesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Species controller.
 */
class SpeciesController extends Controller
{
    /**
     * A constant that contain the api url.
     */
    const NCBI_TAXONOMY_API_LINK = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&id=';

    /**
     * List species authorized for the user.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/species", name="species_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $speciesList = $em->getRepository('AppBundle:Species')->getAllSpeciesWithAvailableStrains($this->getUser());

        return $this->render('species/index.html.twig', array(
            'speciesList' => $speciesList,
        ));
    }

    /**
     * View a species.
     *
     * @param $speciesSlug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/species/{speciesSlug}", name="species_view")
     */
    public function viewAction($speciesSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getOneSpeciesWithStrains($speciesSlug);

        if (null === $species) {
            throw $this->createNotFoundException("This species doen't exists.");
        }

        return $this->render('species/view.html.twig', array(
            'species' => $species,
        ));
    }

    /**
     * List all the species in the admin section.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/species/list", name="species_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getAllSpeciesWithAvailableStrains($this->getUser());

        return $this->render('species/list.html.twig', array(
            'speciesList' => $species,
        ));
    }

    /**
     * Add a species.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/species/add", name="species_add")
     */
    public function addAction(Request $request)
    {
        $species = new Species();

        $form = $this->createForm(SpeciesType::class, $species);
        $form->add('save', SubmitType::class, array(
            'label' => 'Add a species',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($species);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully added.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit a species.
     *
     * @param Request $request
     * @param Species $species
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/species/{id}/edit", name="species_edit")
     */
    public function editAction(Request $request, Species $species)
    {
        $form = $this->createForm(SpeciesType::class, $species);
        $form->add('save', SubmitType::class, array(
            'label' => 'Edit the species',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully edited.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/edit.html.twig', array(
            'species' => $species,
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete a species.
     *
     * @param Request $request
     * @param Species $species
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/species/{id}/delete", name="species_delete")
     */
    public function deleteAction(Request $request, Species $species)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, array(
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($species);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully deleted.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/delete.html.twig', array(
            'species' => $species,
            'form' => $form->createView(),
        ));
    }

    /**
     * Consult the ncbi taxonomy api, and return a json with the interesting data.
     * Used in AddSpecies for the autocomplete method.
     *
     * @param $taxid
     *
     * @return JsonResponse
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
        $response = array();

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
