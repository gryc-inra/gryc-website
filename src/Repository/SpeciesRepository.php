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

namespace App\Repository;

use App\Entity\User;

class SpeciesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAvailableSpeciesAndStrains(User $user = null)
    {
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
            ->leftJoin('strains.users', 'users')
                ->addSelect('users')
            ->leftJoin('species.seos', 'seos')
                ->addSelect('seos')
            ->where('strains.public = true')
            ->orWhere('users = :user')
                ->setParameter('user', $user)
            ->orderBy('species.scientificName', 'ASC')
            ->addOrderBy('strains.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getAllSpeciesAndStrains()
    {
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
            ->addSelect('strains')
            ->leftJoin('species.seos', 'seos')
            ->addSelect('seos')
            ->orderBy('species.scientificName', 'ASC')
            ->addOrderBy('strains.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getSpeciesAndAvailableStrains($slug, User $user = null)
    {
        $query = $this->createQueryBuilder('species')
            ->leftJoin('species.strains', 'strains')
                ->addSelect('strains')
                ->orderBy('strains.name', 'ASC')
            ->leftJoin('strains.users', 'users')
                ->addSelect('users')
            ->leftJoin('species.seos', 'seos')
                ->addSelect('seos')
            ->where('species.slug = :slug')
            ->andWhere('strains.public = true OR users = :user')
            ->setParameters([
                'slug' => $slug,
                'user' => $user,
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findAllWithSeo()
    {
        $query = $this
            ->createQueryBuilder('species')
                ->leftJoin('species.strains', 'strains')
                    ->addSelect('strains')
                ->leftJoin('species.seos', 'species_seos')
                    ->addSelect('species_seos')
                ->leftJoin('strains.seos', 'strains_seos')
                    ->addSelect('strains_seos')
            ->getQuery();

        return $query->getResult();
    }
}
