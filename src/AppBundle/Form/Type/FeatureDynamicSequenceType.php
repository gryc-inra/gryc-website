<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => [
                new Callback([
                    'callback' => function ($data, ExecutionContextInterface $executionContectInterface) {
                        if ($data['upstream'] > 0 && (!$data['showUtr'] || !$data['showIntron'])) {
                            $executionContectInterface->buildViolation('You cannot set upstream if you do not display UTRs and introns.')
                                ->atPath('[upstream]')
                                ->addViolation()
                            ;
                        }

                        if ($data['downstream'] > 0 && (!$data['showUtr'] || !$data['showIntron'])) {
                            $executionContectInterface->buildViolation('You cannot set downstream if you do not display UTRs and introns.')
                                ->atPath('[downstream]')
                                ->addViolation()
                            ;
                        }
                    },
                ]),
            ],
        ]);
    }
}
