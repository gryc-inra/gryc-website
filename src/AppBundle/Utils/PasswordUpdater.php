<?php

namespace AppBundle\Utils;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordUpdater
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePassword(User $user)
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);
        $user->eraseCredentials();
    }
}
