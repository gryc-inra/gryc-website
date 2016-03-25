<?php

namespace AppBundle\Form\Type;

use AppBundle\Repository\StrainRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorizedStrains', EntityType::class, array(
                'class' => 'AppBundle\Entity\Strain',
                'query_builder' => function (StrainRepository $sr) {
                    return $sr->createQueryBuilder('strain')
                        ->leftJoin('strain.species', 'species')
                            ->addSelect('species')
                        ->orderBy('species.scientificName')
                        ->addOrderBy('strain.name');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
                'group_by' => 'Species.scientificName',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}
