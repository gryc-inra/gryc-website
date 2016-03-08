<?php
// src/AppBundle/Form/Type/RegistrationType.php

namespace Grycii\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('recaptcha', EWZRecaptchaType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new RecaptchaTrue(),
                ), ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
