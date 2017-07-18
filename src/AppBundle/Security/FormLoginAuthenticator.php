<?php

namespace AppBundle\Security;

use AppBundle\Utils\LoginBruteForce;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $router;
    private $encoder;
    private $loginBruteForce;

    public function __construct(Router $router, UserPasswordEncoderInterface $encoder, LoginBruteForce $loginBruteForce)
    {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->loginBruteForce = $loginBruteForce;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }

    public function getCredentials(Request $request)
    {
        if ($request->request->has('_username')) {
            return [
                'username' => $request->request->get('_username'),
                'password' => $request->request->get('_password'),
            ];
        }

        return;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        // Check if the asked username is under bruteforce attack, or if client process to a bruteforce attack
        $this->loginBruteForce->isBruteForce($username);

        // Catch the UserNotFound execption, to avoid gie informations about users in database
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
}
