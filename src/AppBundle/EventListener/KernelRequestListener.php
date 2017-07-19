<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class KernelRequestListener
{
    private $tokenStorage;
    private $authorizationChecker;
    private $session;
    private $router;

    public function __construct(
        TokenStorage $tokenStorage,
        AuthorizationChecker $authorizationChecker,
        Session $session,
        RouterInterface $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->isUserLoggedIn()) {
            return;
        }

        $sessionId = $this->session->getId();
        $user = $this->tokenStorage->getToken()->getUser();

        // If the sessionId and the sessionId in database are equal: this is the latest connected user
        if ($sessionId === $user->getSessionId()) {
            return;
        }

        $this->session->getFlashBag()->add('danger', 'You have been logged out, because another person logged in whith your credentials.');
        $redirectUrl = $this->router->generate('logout');
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
