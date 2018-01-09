<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
