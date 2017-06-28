<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ReverseComplementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(?:>[\w\W]+\s(?:[A-Z]+\s?)+\s*)+$/i',
                        'message' => 'This is not a valid FASTA.',
                    ]),
                ],
                'attr' => [
                    'rows' => 10,
                ],
                'label' => 'Fasta',
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'Reverse-Complement' => 'reverse-complement',
                    'Reverse' => 'reverse',
                    'Complement' => 'complement',
                ],
            ])
        ;
    }
}
