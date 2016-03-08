<?php
// src/AppBundle/Form/Type/ProfileType.php

namespace Grycii\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('company');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
