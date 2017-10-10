<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticPagesController extends Controller
{
    /**
     * @Route("/publisher", name="publisher")
     */
    public function publisherAction()
    {
        return $this->render('static_pages/publisher.html.twig');
    }

    /**
     * @Route("/privacy-policy", name="privacy-policy")
     */
    public function privacyPolicyAction()
    {
        return $this->render('static_pages/privacyPolicy.html.twig');
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction()
    {
        return $this->render('static_pages/faq.html.twig');
    }

    /**
     * @Route("/tools", name="tools")
     */
    public function toolsAction()
    {
        return $this->render('tools/index.html.twig');
    }
}
