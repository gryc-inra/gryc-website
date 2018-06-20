<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace App\Controller;

use App\Events;
use App\Form\Type\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $userManager = $this->get('App\Service\UserManager');
        $user = $userManager->createUser();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the user
            $userManager->updateUser($user);

            // Dispatch an event
            $event = new GenericEvent($user);
            $eventDispatcher->dispatch(Events::USER_REGISTERED, $event);

            $this->addFlash('success', 'You have been successfully registered, before login you must validate your email address by clicking on the link in the mail that was sent to you.');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'user/registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/register/confirm/{token}", name="user_activation")
     */
    public function activateAction($token)
    {
        $userManager = $this->get('App\Service\UserManager');
        $user = $userManager->findUserByConfirmationToken($token);

        // If the user doesn't exists
        // The token doesn't exists too
        if (null === $user) {
            $this->addFlash('warning', 'This token doesn\'t exists.');

            return $this->redirectToRoute('login');
        }

        // If the user is already enabled, return
        if ($user->isEnabled()) {
            $this->addFlash('warning', 'Your account is already activated.');

            return $this->redirectToRoute('login');
        }

        // Active the account
        $user->setEnabled(true);
        $userManager->updateUser($user);

        // Add notification
        $this->addFlash('success', 'Your account have been successfully activated. You can now connect.');

        return $this->redirectToRoute('login');
    }
}
