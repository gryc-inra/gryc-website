<?php

namespace AppBundle\EventListener;

use AppBundle\Service\LoginBruteForce;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private $loginBruteForce;

    public function __construct(LoginBruteForce $loginBruteForce)
    {
        $this->loginBruteForce = $loginBruteForce;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $this->loginBruteForce->addFailedLogin($event);
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $this->loginBruteForce->resetUsername($event->getAuthenticationToken()->getUsername());
    }
}
