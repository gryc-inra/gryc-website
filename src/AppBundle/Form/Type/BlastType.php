<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Strain;
use AppBundle\Utils\CartManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BlastType extends AbstractType
{
    const ANONYMOUS_QUERY_LENGTH = 10100;
    const AUTHENTICATED_QUERY_LENGTH = 50100;

    private $tokenStorage;
    private $authorizationChecker;
    private $cartManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, CartManager $cartManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->cartManager = $cartManager;
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
            ->add('strainsFilter', EntityType::class, [
                'class' => 'AppBundle\Entity\Clade',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('clade')
                        ->leftJoin('clade.species', 'species')
                            ->addSelect('species')
                        ->leftJoin('species.strains', 'strain')
                            ->addSelect('strain')
                        ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                        ->orderBy('clade.name', 'asc')
                        ->where('strain.public = true')
                        ->orWhere('authorizedUsers = :user')
                        ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                },
                'choice_value' => 'name',
                'choice_label' => 'name',
                'placeholder' => 'All',
                'required' => false,
                'mapped' => false,
            ])
            ->add('strains', EntityType::class, [
                'class' => 'AppBundle\Entity\Strain',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('strain')
                        ->leftJoin('strain.species', 'species')
                            ->addSelect('species')
                        ->leftJoin('species.clade', 'clade')
                            ->addSelect('clade')
                        ->leftJoin('strain.authorizedUsers', 'authorizedUsers')
                        ->orderBy('species.scientificName', 'asc')
                        ->addOrderBy('strain.name', 'asc')
                        ->where('strain.public = true')
                        ->orWhere('authorizedUsers = :user')
                            ->setParameter('user', $this->tokenStorage->getToken()->getUser());
                },
                'choice_label' => function (Strain $strain) {
                    return $strain->getSpecies()->getScientificName().' ('.$strain->getName().')';
                },
                'choice_attr' => function (Strain $strain) {
                    return ['data-clade' => $strain->getSpecies()->getClade()->getName()];
                },
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                    ]),
                ],
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
            ->add('cart', CartType::class, [
                'mapped' => false,
                'label' => 'Cart parameters',
            ])
            ->add('blast', SubmitType::class, ['label' => 'Blast'])
            ->add('blastFromCart', SubmitType::class, ['label' => 'Blast from cart'])
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
                    'pattern' => '/^(?:>[\w\W]+\s(?:[A-Z]+\s?)+\s*)+$/i',
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

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();

                dump($data);

                // If the user have clicked on Align from cart
                if (isset($data['blastFromCart'])) {
                    // Cart Fasta Parameters
                    $parameters = $data['cart'];
                    $parameters['intronSplicing'] = isset($parameters['intronSplicing']) ? $parameters['intronSplicing'] : false;

                    // Get fasta
                    $fasta = $this->cartManager->getCartFasta($parameters['type'], $parameters['feature'], (bool) $parameters['intronSplicing'], (int) $parameters['upstream'], (int) $parameters['downstream']);
                    // Replace the query by the generated fasta and edit the data
                    $data['query'] = $fasta;
                    $event->setData($data);
                }
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
