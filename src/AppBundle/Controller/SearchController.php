<?php

namespace AppBundle\Controller;

use AppBundle\Form\QuickSearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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