<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\AdvancedSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Search engine controller.
 */
class SearchController extends Controller
{
    const HITS_PER_PAGE = 50;

    /**
     * @Route("/search", options={"expose"=true}, name="quick-search")
     */
    public function quickSearchAction(Request $request)
    {
        $keyword = null !== $request->get('q') ? $request->get('q') : '';

        $repositoryManager = $this->get('fos_elastica.manager');
        $repository = $repositoryManager->getRepository('AppBundle:Locus');
        $results = $repository->findByNameNoteAnnotation($keyword, $this->getUser());

        // Return the view
        return $this->render('search\quickSearch.html.twig', [
            'search' => $keyword,
            'results' => $results,
        ]);
    }

    /**
     * @Route("/advanced-search", name="advanced-search")
     * @Route("/advanced-search/{search}", name="advanced-search-with-keyword")
     */
    public function advancedSearchAction(Request $request, $search = null)
    {
        $data = null !== $search ? ['search' => $search] : null;
        $form = $this->createForm(AdvancedSearchType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $repositoryManager = $this->get('fos_elastica.manager');
            $repository = $repositoryManager->getRepository('AppBundle:Locus');
            $results = $repository->findByNameNoteAnnotation($data['search'], $this->getUser(), $data['strains']);

            // Return the view
            return $this->render('search\advancedSearch.html.twig', [
                'form' => $form->createView(),
                'results' => $results,
            ]);
        }

        // Return the view
        return $this->render('search\advancedSearch.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
