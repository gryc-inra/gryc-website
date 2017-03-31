<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StrainSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seos', CollectionType::class, [
                'entry_type'    => SeoType::class,
                'entry_options' => [
                    'required' => false,
                ],
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Strain',
        ]);
    }
}
