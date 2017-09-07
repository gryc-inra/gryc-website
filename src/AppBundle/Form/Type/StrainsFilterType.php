<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Strain;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Count;

class StrainsFilterType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filter', EntityType::class, [
                'class' => 'AppBundle\Entity\Clade',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('clade')
                        ->leftJoin('clade.species', 'species')
                        ->addSelect('species')
                        ->leftJoin('species.strains', 'strain')
                        ->addSelect('strain')
                        ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                        ->orderBy('clade.name', 'asc')
                        ->where('strain.public = true')
                        ->orWhere('authorizedUsers = :user')
                        ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                },
                'choice_value' => 'name',
                'choice_label' => 'name',
                'placeholder' => 'All',
                'required' => false,
                'mapped' => false,
            ])
            ->add('strains', EntityType::class, [
                'class' => 'AppBundle\Entity\Strain',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('strain')
                        ->leftJoin('strain.species', 'species')
                        ->addSelect('species')
                        ->leftJoin('species.clade', 'clade')
                        ->addSelect('clade')
                        ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                        ->orderBy('species.scientificName', 'asc')
                        ->addOrderBy('strain.name', 'asc')
                        ->where('strain.public = true')
                        ->orWhere('authorizedUsers = :user')
                        ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                },
                'choice_label' => function (Strain $strain) {
                    return $strain->getSpecies()->getScientificName().' ('.$strain->getName().')';
                },
                'choice_attr' => function (Strain $strain) {
                    return ['data-clade' => $strain->getSpecies()->getClade()->getName()];
                },
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true
        ));
    }
}
