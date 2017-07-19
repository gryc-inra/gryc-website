<?php

namespace AppBundle\EventListener;

use AppBundle\Utils\UserManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityInteractiveLoginListener
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
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
}
