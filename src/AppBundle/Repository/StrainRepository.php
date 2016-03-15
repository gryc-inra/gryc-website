<?php

namespace Grycii\AppBundle\Repository;

/**
 * StrainRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StrainRepository extends \Doctrine\ORM\EntityRepository
{
    public function getStrainWithSpeciesAndChromosomes($name)
    {
        $query = $this
            ->createQueryBuilder('strain')
            ->where('strain.name = :name')
            ->setParameter('name', $name)
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->leftJoin('strain.chromosomes', 'c')
                ->addSelect('c')
                ->orderBy('c.name', 'ASC')
            ->getQuery();

        return $query->getSingleResult();
    }

    public function getStrainWithFlatFiles($name)
    {
        $query = $this
            ->createQueryBuilder('strain')
                ->where('strain.name = :name')
                ->setParameter('name', $name)
            ->leftJoin('strain.chromosomes', 'chromosomes')
                ->addSelect('chromosomes')
                ->orderBy('chromosomes.name', 'ASC')
            ->leftJoin('chromosomes.flatFiles', 'flatFiles')
                ->addSelect('flatFiles')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
