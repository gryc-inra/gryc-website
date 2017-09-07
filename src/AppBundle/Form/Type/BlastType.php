<?php

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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
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
            ])
            ->add('strainsFilter', StrainsFilterType::class, [
                'data_class' => Blast::class,
            ])
            ->add('filter', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('evalue', NumberType::class, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('gapped', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $tool) {
            if ('blastp' === $tool || 'blastx' === $tool) {
                $databaseChoices = [
                    'CDS (protein)' => 'cds_prot',
                ];
            } else {
                $databaseChoices = [
                    'CDS (nucleotides)' => 'cds_nucl',
                    'Chromosomes' => 'chr',
                ];
            }

            $form->add('database', ChoiceType::class, [
                'choices' => $databaseChoices,
            ]);

            $queryConstrainst = [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^(?:>[\w-]+\R(?:[A-Z*]+\R?)+\R*)+$/i',
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
                'label' => 'Fasta',
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $blast = $event->getData();
                $formModifier($event->getForm(), $blast->getTool());
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
