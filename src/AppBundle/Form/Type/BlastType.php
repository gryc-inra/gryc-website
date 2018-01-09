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

use AppBundle\Entity\Blast;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BlastType extends AbstractType
{
    const ANONYMOUS_QUERY_LENGTH = 10100;
    const AUTHENTICATED_QUERY_LENGTH = 50100;

    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tool', ChoiceType::class, [
                'choices' => [
                    'blastn' => 'blastn',
                    'blastp' => 'blastp',
                    'tblastn' => 'tblastn',
                    'blastx' => 'blastx',
                    'tblastx' => 'tblastx',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('strainsFiltered', StrainsFilteredType::class)
            ->add('filter', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-custom',
                ],
            ])
            ->add('gapped', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-custom',
                ],
            ])
            ->add('evalue', NumberType::class, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $tool) {
            $form->add('database', ChoiceType::class, [
                'choices' => [
                    'CDS (protein)' => 'cds_prot',
                    'CDS (nucleotides)' => 'cds_nucl',
                    'Chromosomes' => 'chr',
                ],
                'choice_attr' => function ($val) use ($tool) {
                    $array = [];

                    if (!in_array($val, Blast::TOOLS_DATABASES[$tool], true)) {
                        $array = ['disabled' => 'disabled'];
                    } elseif (Blast::TOOLS_DEFAULT_DATABASE[$tool] === $val) {
                        $array = ['checked' => 'checked'];
                    }

                    return $array;
                },
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

            // Query field and constrainst (depending of the user status)
            $queryConstrainst = [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^(?:(?:>[\w-]+\R(?:[A-Z*]+\R?)+\R*)+)|(?:[A-Z*]+\R?)+$/i',
                    'message' => 'This is not a valid FASTA.',
                ]),
            ];

            if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $queryConstrainst[] =
                    new Length([
                        'max' => self::AUTHENTICATED_QUERY_LENGTH,
                    ])
                ;
            } else {
                $queryConstrainst[] =
                    new Length([
                        'max' => self::ANONYMOUS_QUERY_LENGTH,
                        'maxMessage' => 'This value is too long. It should have {{ limit }} characters or less. Create an account to improve the limit at 100.000 characters.',
                    ])
                ;
            }

            $form->add('query', TextareaType::class, [
                'constraints' => $queryConstrainst,
                'attr' => [
                    'rows' => 10,
                ],
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $tool = $event->getData()->getTool();

                $formModifier($event->getForm(), $tool);
            }
        );

        $builder->get('tool')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $tool = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $tool);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Blast',
        ]);
    }
}
