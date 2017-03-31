<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Search engine controller.
 */
class SearchController extends Controller
{
    /**
     * Quick search.
     * Do the search and return the results.
     *
     * @param $search
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/quick-search/{search}", name="quick-search")
     */
    public function quickSearchAction($search)
    {
        $repositoryManager = $this->container->get('fos_elastica.manager');
        $repository = $repositoryManager->getRepository('AppBundle:User');
        $results = $repository->findWithCustomQuery($search);

        return $this->render('search\quickSearchResults.html.twig', [
            'search' => $search,
            'results' => $results,
        ]);
    }
}
