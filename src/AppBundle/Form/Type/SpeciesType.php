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

use AppBundle\Repository\CladeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxId', IntegerType::class, [
                'label' => 'TaxId',
                'attr' => [
                    'data-help' => 'The taxID of the species, you can find it <a target="_blank" href="https://www.ncbi.nlm.nih.gov/taxonomy">here</a>.',
                ],
                'required' => false,
            ])
            ->add('clade', EntityType::class, [
                'class' => 'AppBundle:Clade',
                'choice_label' => 'name',
                'query_builder' => function (CladeRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'placeholder' => '-- select a clade --',
            ])
            ->add('scientificName', TextType::class)
            ->add('genus', TextType::class)
            ->add('species', TextType::class)
            ->add('geneticCode', IntegerType::class)
            ->add('mitoCode', IntegerType::class)
            ->add('lineages', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('synonyms', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Species',
        ]);
    }
}
