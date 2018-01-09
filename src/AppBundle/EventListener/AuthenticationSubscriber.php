<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
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
