<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Sequence type',
                'choices' => [
                    'Amino acids' => 'prot',
                    'Nucleotides' => 'nuc',
                ],
                'required' => true,
                'data' => 'nuc',
            ])
            ->add('feature', ChoiceType::class, [
                'label' => 'Feature level',
                'choices' => [
                    'Locus' => 'locus',
                    'Transcript' => 'feature',
                    'Product' => 'product',
                ],
                'required' => true,
                'data' => 'locus',
            ])
            ->add('intronSplicing', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'required' => true,
                'data' => false,
            ])
            ->add('upstream', IntegerType::class, [
                'data' => 0,
            ])
            ->add('downstream', IntegerType::class, [
                'data' => 0,
            ])
        ;
    }
}
