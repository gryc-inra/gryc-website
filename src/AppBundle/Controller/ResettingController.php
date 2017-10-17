<?php

namespace AppBundle\Controller;

use AppBundle\Events;
use AppBundle\Form\Type\ResetPasswordType;
use AppBundle\Form\Type\ResettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class ResettingController extends Controller
{
    /**
     * @Route("/resetting/request", name="user_resetting_request")
     */
    public function resettingRequestAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(ResettingType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            // Get user
            $userManager = $this->get('AppBundle\Utils\UserManager');
            $user = $userManager->findUserBy(['email' => $email]);

            // Control the User
            if (null === $user) {
                $this->addFlash('warning', 'There is no user with this email address.');

                return $this->redirectToRoute('user_resetting_request');
            }

            if (!$user->isEnabled()) {
                $this->addFlash('warning', 'Your account is not activated.');

                return $this->redirectToRoute('login');
            }

            // Generate a token, to reset password
            $userManager->generateToken($user);
            $userManager->updateUser($user);

            // Dispatch an event
            $event = new GenericEvent($user);
            $eventDispatcher->dispatch(Events::USER_RESET, $event);

            // Add a flash message
            $this->addFlash('success', 'An email containing the password reset procedure has been sent to you.');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'user/resetting/resetting_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/resetting/{username}/{token}", name="user_resetting")
     */
    public function resettingAction($username, $token, Request $request)
    {
        $username = rawurldecode($username);
        $token = rawurldecode($token);

        // Get user
        $userManager = $this->get('AppBundle\Utils\UserManager');
        $user = $userManager->findUserBy(['email' => $username]);

        // Check the User
        if (!$user->isEnabled()) {
            $this->addFlash('warning', 'Your account is not activated.');

            return $this->redirectToRoute('login');
        }

        if (null === $user->getConfirmationToken() || !hash_equals($user->getConfirmationToken(), $token)) {
            $this->addFlash('warning', 'The confirmation token is not valid.');

            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the password
            $userManager->removeToken($user);
            $userManager->updateUser($user);

            // Reinit the brute-force counter for this username
            $this->get('AppBundle\Utils\LoginBruteForce')->resetUsername($username);

            // Add a flash message
            $this->addFlash('success', 'Your password have been successfully changed. You can now log in with this new one.');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/resetting/resetting.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
