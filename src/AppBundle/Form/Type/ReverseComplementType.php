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
                        'pattern' => '/^(?:>[\w\W]+\s(?:[ATUGCYRSWKMBDHVN]+\s?)+\s*)+$/i',
                        'message' => 'This is not in a valid FASTA format. Only the letters A, T, U, G, C, Y, R, S, W, K, M, B, D, H, V, and N are allowed.',
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
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline radio-custom',
                ],
                'data' => 'reverse-complement',
            ])
        ;
    }
}
