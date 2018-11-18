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

namespace App\EventListener;

use App\Service\UserManager;
use Symfony\Bundle\SecurityBundle\Templating\Helper\LogoutUrlHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class OneSessionPerUserSubscriber
{
    private $userManager;
    private $tokenStorage;
    private $authorizationChecker;
    private $session;
    private $logoutUrlHelper;

    public function __construct(
        UserManager $userManager,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface $session,
        LogoutUrlHelper $logoutUrlHelper
    ) {
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->logoutUrlHelper = $logoutUrlHelper;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $session->has('id'); // Just to fix a bug on Remember Me
        $user = $event->getAuthenticationToken()->getUser();

        // Set the session ID on user and save it in database
        $user->setSessionId($session->getId());
        $this->userManager->updateUser($user);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->isUserLoggedIn()) {
            return;
        }

        // Don't check if admin impersonnate a user
        if ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            return;
        }

        $sessionId = $this->session->getId();
        $user = $this->tokenStorage->getToken()->getUser();

        // If the sessionId and the sessionId in database are equal: this is the latest connected user
        if ($sessionId === $user->getSessionId()) {
            return;
        }

        $this->session->getFlashBag()->add('danger', 'You have been logged out, because another person logged in whith your credentials.');
        $redirectUrl = $this->logoutUrlHelper->getLogoutPath('main');
        $response = new RedirectResponse($redirectUrl);

        $event->setResponse($response);
    }

    protected function isUserLoggedIn()
    {
        try {
            return $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            // Ignoring this exception.
        }

        return false;
    }
}
