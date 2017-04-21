<?php

namespace AppBundle\Controller;

use AppBundle\SearchRepository\GlobalRepository;
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

        // Get the query
        $repository = new GlobalRepository();
        $query = $repository->searchQuery($keyword, $this->getUser());

        // Execute the query
        $mngr = $this->get('fos_elastica.index_manager');
        $search = $mngr->getIndex('app')->createSearch();
        $search->addType('locus');
        $search->addType('feature');
        $search->addType('product');
        $resultSet = $search->search($query, self::HITS_PER_PAGE);
        $transformer = $this->get('fos_elastica.elastica_to_model_transformer.collection.app');
        $results = $transformer->transform($resultSet->getResults());

        // Return the view
        return $this->render('search\quickSearch.html.twig', [
            'search' => $keyword,
            'results' => $results,
        ]);
    }
}
