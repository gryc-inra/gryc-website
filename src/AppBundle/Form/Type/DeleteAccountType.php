<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteAccountType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('plainPassword')
        ;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChangePasswordType::class;
    }
}
