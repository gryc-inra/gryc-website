<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Strain;

/**
 * LocusRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LocusRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLocus($locusName)
    {
        $query = $this->createQueryBuilder('locus')
            ->leftJoin('locus.features', 'features')
                ->addSelect('features')
            ->leftJoin('features.productsFeatures', 'products')
                ->addSelect('products')
            ->leftJoin('locus.chromosome', 'chromosome')
                ->addSelect('chromosome')
            ->leftJoin('chromosome.dnaSequence', 'dnaSequence')
                ->addSelect('dnaSequence')
            ->leftJoin('chromosome.strain', 'strain')
                ->addSelect('strain')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->where('locus.name = :locusName')
                ->setParameter('locusName', $locusName)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findLocusFromFeature($featureName)
    {
        $query = $this->createQueryBuilder('locus')
            ->leftJoin('locus.features', 'features')
                ->addSelect('features')
            ->leftJoin('features.productsFeatures', 'products')
                ->addSelect('products')
            ->leftJoin('locus.chromosome', 'chromosome')
                ->addSelect('chromosome')
            ->leftJoin('chromosome.dnaSequence', 'dnaSequence')
                ->addSelect('dnaSequence')
            ->leftJoin('chromosome.strain', 'strain')
                ->addSelect('strain')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->where('features.name = :featureName')
                ->setParameter('featureName', $featureName)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findLocusFromProduct($productName)
    {
        $qb = $this->createQueryBuilder('locus')
            ->leftJoin('locus.features', 'features')
                ->addSelect('features')
            ->leftJoin('features.productsFeatures', 'products')
                ->addSelect('products')
            ->leftJoin('locus.chromosome', 'chromosome')
                ->addSelect('chromosome')
            ->leftJoin('chromosome.dnaSequence', 'dnaSequence')
                ->addSelect('dnaSequence')
            ->leftJoin('chromosome.strain', 'strain')
                ->addSelect('strain')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species');

        if (is_array($productName)) {
            $qb->where('products.name IN(:productName)')
                ->setParameter('productName', $productName);

            return $qb->getQuery()->getResult();
        } else {
            $qb->where('products.name = :productName')
                ->setParameter('productName', $productName);

            return $qb->getQuery()->getOneOrNullResult();
        }
    }

    public function findLocusById($ids)
    {
        $query = $this->createQueryBuilder('locus')
            ->leftJoin('locus.chromosome', 'chromosome')
                ->addSelect('chromosome')
            ->leftJoin('chromosome.dnaSequence', 'dnaSequence')
                ->addSelect('dnaSequence')
            ->leftJoin('chromosome.strain', 'strain')
                ->addSelect('strain')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->where('locus.id IN (:id)')
                ->setParameter('id', $ids)
            ->getQuery();

        return $query->getResult();
    }

    public function findLocusFromStrain(Strain $strain)
    {
        $query = $this->createQueryBuilder('locus')
            ->leftJoin('locus.chromosome', 'chromosome')
                ->addSelect('chromosome')
            ->leftJoin('chromosome.strain', 'strain')
                ->addSelect('strain')
            ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                ->addSelect('authorizedUsers')
            ->where('strain = :strain')
                ->setParameter('strain', $strain->getId())

            ->getQuery();

        return $query->getResult();
    }
}
