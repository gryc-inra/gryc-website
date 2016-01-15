<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class QuickSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, array(
                'attr' => array(
                    'rows' => 20,
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 2)),
                )
            ));
    }

    //public function configureOptions(OptionsResolver $resolver)
    //{
    //    $resolver->setDefaults(array(
    //        'csrf_protection' => false,
    //    ));
    //}
}
