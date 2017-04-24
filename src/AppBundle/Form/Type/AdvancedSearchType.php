<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdvancedSearchType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Search',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('strains', EntityType::class, [
                'class' => 'AppBundle\Entity\Strain',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('strain')
                        ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                        ->leftJoin('strain.species', 'species')
                        ->where('authorizedUsers = :user')
                        ->orWhere('strain.public = true')
                        ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                },
                'choice_label' => function ($strain) {
                    return $strain->getSpecies()->getScientificName().' '.$strain->getName();
                },
                'group_by' => 'species.scientificName',
                'multiple' => true,
            ])
        ;
    }
}
