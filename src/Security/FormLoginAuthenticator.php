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

namespace App\Security;

use App\Service\LoginBruteForce;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    private $router;
    private $encoder;
    private $loginBruteForce;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder, LoginBruteForce $loginBruteForce)
    {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->loginBruteForce = $loginBruteForce;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return $request->request->has('_username');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        // Check if the asked username is under bruteForce attack, or if client process to a bruteForce attack
        $this->loginBruteForce->isBruteForce($username);

        // Catch the UserNotFound exception, to avoid give informations about users in database
        try {
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Bad credentials.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        $passwordValid = $this->encoder->isPasswordValid($user, $credentials['password']);

        if (!$passwordValid) {
            throw new AuthenticationException('Bad credentials.');
        }

        return true;
    }

    /**
     * Override to change what happens after successful authentication.
     *
     * @param string $providerKey
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        $targetPath = null;

        // if the user hit a secure page and start() was called, this was
        // the URL they were on, and probably where you want to redirect to
        if ($request->getSession() instanceof SessionInterface) {
            $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        }

        if (!$targetPath) {
            $targetPath = $this->getDefaultSuccessRedirectUrl();
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * Override to change what happens after a bad username/password is submitted.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        if ($request->getSession() instanceof SessionInterface) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    public function supportsRememberMe()
    {
        return true;
    }

    /**
     * Return the URL to the login page.
     */
    protected function getLoginUrl(): string
    {
        return $this->router->generate('login');
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }
}
