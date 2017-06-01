<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage
            return $this->redirect($this->generateUrl('homepage'));
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
