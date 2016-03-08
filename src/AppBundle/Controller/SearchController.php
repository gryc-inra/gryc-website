<?php

namespace Grycii\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SearchController extends Controller
{
    /**
     * @Route("/quick-search/{search}", name="quick-search")
     */
    public function quickSearchAction($search)
    {
        $repositoryManager = $this->container->get('fos_elastica.manager');
        $repository = $repositoryManager->getRepository('AppBundle:User');
        $results = $repository->findWithCustomQuery($search);

        return $this->render('search\quickSearchResults.html.twig', array(
            'search' => $search,
            'results' => $results,
        ));
    }
}
