<?php

namespace AppBundle\Form\Type;

use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StrainRightsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorizedUsers', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('user')
                        ->orderBy('user.lastName', 'ASC')
                        ->addOrderBy('user.firstName', 'ASC');
                },
                'choice_label' => 'fullName',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Strain',
        ]);
    }
}
