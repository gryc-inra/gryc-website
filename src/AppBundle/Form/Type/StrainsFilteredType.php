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

namespace AppBundle\Form\Type;

use AppBundle\Entity\Strain;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Count;

class StrainsFilteredType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filter', EntityType::class, [
                'class' => 'AppBundle\Entity\Clade',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('clade')
                        ->leftJoin('clade.species', 'species')
                            ->addSelect('species')
                        ->leftJoin('species.strains', 'strain')
                            ->addSelect('strain')
                        ->leftJoin('strain.users', 'users')
                        ->orderBy('clade.name', 'asc');

                    // If we want filtered Strains for the user
                    if (true === $options['allowed_strains']) {
                        $qb = $qb->where('strain.public = true')
                            ->orWhere('users = :user')
                            ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                    }

                    return $qb;
                },
                'choice_value' => 'name',
                'choice_label' => 'name',
                'placeholder' => 'All',
                'required' => false,
                'mapped' => false,
            ])
            ->add('strains', EntityType::class, [
                'class' => 'AppBundle\Entity\Strain',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('strain')
                        ->leftJoin('strain.species', 'species')
                            ->addSelect('species')
                        ->leftJoin('species.clade', 'clade')
                            ->addSelect('clade')
                        ->leftJoin('strain.users', 'users')
                        ->orderBy('species.scientificName', 'asc')
                        ->addOrderBy('strain.name', 'asc');

                    // If we want filtered Strains for the user
                    if (true === $options['allowed_strains']) {
                        $qb = $qb->where('strain.public = true')
                            ->orWhere('users = :user')
                            ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                    }

                    return $qb;
                },
                'choice_label' => function (Strain $strain) {
                    return $strain->getSpecies()->getScientificName().' ('.$strain->getName().')';
                },
                'choice_attr' => function (Strain $strain) {
                    return ['data-clade' => $strain->getSpecies()->getClade()->getName()];
                },
                'multiple' => true,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'checkbox-custom',
                ],
                'by_reference' => false,
                'constraints' => [
                    new Count([
                        'min' => $options['min_strains'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);

        $resolver->setDefault('allowed_strains', true);
        $resolver->setDefault('min_strains', 1);
    }
}
