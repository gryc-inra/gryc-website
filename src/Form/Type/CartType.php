<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

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
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 1000,
                    ]),
                ],
            ])
            ->add('downstream', IntegerType::class, [
                'data' => 0,
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 1000,
                    ]),
                ],
            ])
        ;
    }
}
