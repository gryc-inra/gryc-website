<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'class' => 'AppBundle:Species',
                'choice_label' => 'scientificname',
            ))
            ->add('name')
            ->add('synonymes', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ))
            ->add('length', NumberType::class, array(
                'disabled' => true,
            ))
            ->add('gc', NumberType::class, array(
                'disabled' => true,
            ))
            ->add('cdsCount', NumberType::class, array(
                'disabled' => true,
            ))
            ->add('status', TextType::class, array(
                'disabled' => true,
            ))
            ->add('public')
            ->add('typeStrain')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Strain',
        ));
    }
}
