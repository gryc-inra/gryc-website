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
            ->add('authorizedUsers', EntityType::class, array(
                'class' => 'AppBundle\Entity\User',
                'query_builder' => function (UserRepository $ur) {
                  return $ur->createQueryBuilder('u')
                      ->orderBy('u.username', 'ASC');
                },
                'choice_label' => function ($user) {
                    return $user->getUsername().' ('.$user->getFirstName().' '.$user->getLastName().')';
                },
                'by_reference' => false,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Strain',
        ));
    }
}
