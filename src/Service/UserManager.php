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

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    protected $entityManager;
    protected $repository;
    protected $passwordUpdater;
    protected $tokenGenerator;

    public function __construct(PasswordUpdater $passwordUpdater, EntityManagerInterface $em, TokenGenerator $tokenGenerator)
    {
        $this->entityManager = $em;
        $this->repository = $em->getRepository('App:User');
        $this->passwordUpdater = $passwordUpdater;
        $this->tokenGenerator = $tokenGenerator;
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

    public function findUserByEmail($email)
    {
        return $this->findUserBy(['email' => $email]);
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

    public function generateToken(User $user)
    {
        $token = $this->tokenGenerator->generateToken();
        $user->setConfirmationToken($token);
    }

    public function removeToken(User $user)
    {
        $user->setConfirmationToken(null);
    }
}
