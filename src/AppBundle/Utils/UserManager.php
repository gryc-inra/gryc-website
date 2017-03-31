<?php

namespace AppBundle\Utils;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserManager
{
    protected $entityManager;
    protected $repository;
    protected $passwordUpdater;

    public function __construct(PasswordUpdater $passwordUpdater, EntityManager $em)
    {
        $this->entityManager = $em;
        $this->repository = $em->getRepository('AppBundle:User');
        $this->passwordUpdater = $passwordUpdater;
    }

    public function createUser()
    {
        $user = new User();

        return $user;
    }

    public function deleteUser(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    public function findUsers()
    {
        return $this->repository->findAll();
    }

    public function updateUser(User $user, $andFlush = true)
    {
        $this->updatePassword($user);
        $this->entityManager->persist($user);

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function updatePassword(User $user)
    {
        $this->passwordUpdater->encodePassword($user);
    }
}
