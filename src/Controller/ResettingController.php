<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Events;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\ResettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class ResettingController extends Controller
{
    /**
     * @Route("/resetting/request", methods={"GET", "POST"}, name="user_resetting_request")
     */
    public function resettingRequestAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(ResettingType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            // Get user
            $userManager = $this->get('App\Service\UserManager');
            $user = $userManager->findUserBy(['email' => $email]);

            if (null !== $user && $user->isEnabled()) {
                // Generate a token, to reset password
                $userManager->generateToken($user);
                $userManager->updateUser($user);

                // Dispatch an event
                $event = new GenericEvent($user);
                $eventDispatcher->dispatch(Events::USER_RESET, $event);
            }

            // Alway return the same redirection and flash message
            $this->addFlash('success', 'An email containing the password reset procedure has been sent to you.');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'user/resetting/resetting_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/resetting/{username}/{token}", methods={"GET", "POST"}, name="user_resetting")
     */
    public function resettingAction($username, $token, Request $request)
    {
        $username = rawurldecode($username);
        $token = rawurldecode($token);

        // Get user
        $userManager = $this->get('App\Service\UserManager');
        $user = $userManager->findUserBy(['email' => $username]);

        // Check the User
        if (null === $user
            || !$user->isEnabled()
            || null === $user->getConfirmationToken()
            || !hash_equals($user->getConfirmationToken(), $token)
        ) {
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
            $this->get('App\Service\LoginBruteForce')->resetUsername($username);

            // Add a flash message
            $this->addFlash('success', 'Your password have been successfully changed. You can now log in with this new one.');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/resetting/resetting.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
