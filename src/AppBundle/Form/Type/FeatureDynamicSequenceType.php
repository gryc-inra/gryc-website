<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\LessThan;

class FeatureDynamicSequenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('upstream', IntegerType::class, [
                'data' => 0,
                'constraints' => [
                    new LessThan([
                        'value' => 1000,
                    ]),
                ],
            ])
            ->add('downstream', IntegerType::class, [
                'data' => 0,
                'constraints' => [
                    new LessThan([
                        'value' => 1000,
                    ]),
                ],
            ])
            ->add('showUtr', CheckboxType::class, [
                'data' => true,
                'label' => 'Show UTR',
                'required' => false,
            ])
            ->add('showIntron', CheckboxType::class, [
                'data' => true,
                'required' => false,
            ])
        ;
    }
}
