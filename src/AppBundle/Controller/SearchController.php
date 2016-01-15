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
    public function quickSearchFormAction()
    {
        $form = $this->createForm(QuickSearchType::class, null, array(
            'action' => $this->generateUrl('quick-search'),
        ));
        $form->add('submit', SubmitType::class);

        return $this->render('search/quickSearchForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @route("/quick-search", name="quick-search")
     * @Method("POST")
     */
    public function quickSearchAction(Request $request)
    {
        $data = $request->request->get('quick_search');

        if ($data['search'] !== null) {
            $search = $data['search'];

            $repositoryManager = $this->container->get('fos_elastica.manager');
            $repository = $repositoryManager->getRepository('AppBundle:User');
            $results = $repository->findWithCustomQuery($search);

            return $this->render('search\quickSearchResults.html.twig', array(
                'search' => $search,
                'results' => $results,
            ));
        }
    }
}
