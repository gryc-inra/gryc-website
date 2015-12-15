<?php
/**
 * Pages statiques du site.
 *
 * @copyright 2015 BimLip
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Gestion des pages statiques du site.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
 * @Route("/contact")
 */
class DefaultController extends Controller
{
    /**
     * Page d'accueil du site.
     *
     * @return view
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * Page sur la politique de confidentialité.
     *
     * @return view
     *
     * @Route("/privacy-policy", name="privacy-policy")
     */
    public function privacyPolicyAction(Request $request)
    {
        return $this->render('default/privacyPolicy.html.twig');
    }
}
