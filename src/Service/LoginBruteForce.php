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

namespace App\Service;

use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginBruteForce
{
    // Define constants used to define how many tries we allow per IP and login
    // Here: 20/10 mins (IP); 5/10 mins (username)
    const MAX_IP_ATTEMPTS = 20;
    const MAX_USERNAME_ATTEMPTS = 5;
    const TIME_RANGE = 10; // In minutes

    private $cache;
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->cache = new ApcuAdapter();
        $this->requestStack = $requestStack;
    }

    private function getFailedLogins()
    {
        $failedLoginsItem = $this->cache->getItem('failedLogins');
        $failedLogins = $failedLoginsItem->get();

        // If the failedLogins is not an array, contruct it
        if (!\is_array($failedLogins)) {
            $failedLogins = [
                'ip' => [],
                'username' => [],
            ];
        }

        return $failedLogins;
    }

    private function saveFailedLogins($failedLogins)
    {
        $failedLoginsItem = $this->cache->getItem('failedLogins');
        $failedLoginsItem->set($failedLogins);
        $this->cache->save($failedLoginsItem);
    }

    private function cleanFailedLogins($failedLogins, $save = true)
    {
        $actualTime = new \DateTime('now');
        foreach ($failedLogins as &$failedLoginsCategory) {
            foreach ($failedLoginsCategory as $key => $failedLogin) {
                $lastAttempt = clone $failedLogin['lastAttempt'];
                $lastAttempt = $lastAttempt->modify('+'.self::TIME_RANGE.' minute');

                // If the datetime difference is greatest than 15 mins, delete entry
                if ($lastAttempt <= $actualTime) {
                    unset($failedLoginsCategory[$key]);
                }
            }
        }

        if ($save) {
            $this->saveFailedLogins($failedLogins);
        }

        return $failedLogins;
    }

    public function addFailedLogin(AuthenticationFailureEvent $event)
    {
        $clientIp = $this->requestStack->getMasterRequest()->getClientIp();
        $username = $event->getAuthenticationToken()->getCredentials()['username'];

        $failedLogins = $this->getFailedLogins();

        // Add clientIP
        if (array_key_exists($clientIp, $failedLogins['ip'])) {
            ++$failedLogins['ip'][$clientIp]['nbAttempts'];
            $failedLogins['ip'][$clientIp]['lastAttempt'] = new \DateTime('now');
        } else {
            $failedLogins['ip'][$clientIp]['nbAttempts'] = 1;
            $failedLogins['ip'][$clientIp]['lastAttempt'] = new \DateTime('now');
        }

        // Add username
        if (array_key_exists($username, $failedLogins['username'])) {
            ++$failedLogins['username'][$username]['nbAttempts'];
            $failedLogins['username'][$username]['lastAttempt'] = new \DateTime('now');
        } else {
            $failedLogins['username'][$username]['nbAttempts'] = 1;
            $failedLogins['username'][$username]['lastAttempt'] = new \DateTime('now');
        }

        $this->saveFailedLogins($failedLogins);
    }

    // This function can be use, when the user reset his password, or when he is successfully logged
    public function resetUsername($username)
    {
        $failedLogins = $this->getFailedLogins();

        if (array_key_exists($username, $failedLogins['username'])) {
            unset($failedLogins['username'][$username]);
            $this->saveFailedLogins($failedLogins);
        }
    }

    public function isBruteForce($username)
    {
        $failedLogins = $this->getFailedLogins();
        $failedLogins = $this->cleanFailedLogins($failedLogins, true);

        $clientIp = $this->requestStack->getMasterRequest()->getClientIp();

        // If the IP is in the list
        if (array_key_exists($clientIp, $failedLogins['ip'])) {
            if ($failedLogins['ip'][$clientIp]['nbAttempts'] >= self::MAX_IP_ATTEMPTS) {
                throw new AuthenticationException('Too many login attempts. Please try again in '.self::TIME_RANGE.' minutes.');
            }
        }
        // If the username is in the list
        if (array_key_exists($username, $failedLogins['username'])) {
            if ($failedLogins['username'][$username]['nbAttempts'] >= self::MAX_USERNAME_ATTEMPTS) {
                throw new AuthenticationException('Maximum number of login attempts exceeded for user: "'.$username.'". Please try again in '.self::TIME_RANGE.' minutes.');
            }
        }
    }
}
