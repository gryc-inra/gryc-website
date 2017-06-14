<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Error controller.
 */
class ErrorController extends Controller
{
    /**
     * @Route("/404", name="not_found_error")
     */
    public function notFoundAction()
    {
        throw $this->createNotFoundException();
    }
}
