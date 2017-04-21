<?php

namespace AppBundle\Controller;

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

        dump($results);

        // Return the view
        return $this->render('search\quickSearch.html.twig', [
            'search' => $keyword,
            'results' => $results,
        ]);
    }
}
