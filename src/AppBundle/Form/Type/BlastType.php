<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Strain;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BlastType extends AbstractType
{
    const ANONYMOUS_QUERY_LENGTH = 10100;
    const AUTHENTICATED_QUERY_LENGTH = 50100;

    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(TokenStorage $tokenStorage, AuthorizationChecker $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blastType', ChoiceType::class, [
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
                'choice_value' => 'id',
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
            ->add('evalue', NumberType::class)
            ->add('gapped', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $blastType) {
            if ('blastp' === $blastType || 'blastx' === $blastType) {
                $databaseChoices = [
                    'CDS (protein)' => 'cds_prot',
                ];

                $isMatrixDisabled = false;
            } else {
                $databaseChoices = [
                    'CDS (nucleotides)' => 'cds_nucl',
                    'Chromosomes' => 'chr',
                ];

                $isMatrixDisabled = true;
            }

            $form->add('database', ChoiceType::class, [
                'choices' => $databaseChoices,
            ]);

            $queryConstrainst = [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^(?:>[\w\W]+\s(?:[A-Z]+\s?)+\s*)+$/',
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
                if (null === $event->getData()) {
                    $event->setData([
                        'blastType' => 'blastp',
                        'database' => 'cds_prot',
                        'query' => ">my-query\n",
                        'filter' => false,
                        'evalue' => 0.001,
                        'gapped' => true,
                        'maxHits' => 100,
                        'matrix' => 'BLOSUM62',
                    ]);
                }

                $formModifier($event->getForm(), $event->getData()['blastType']);
            }
        );

        $builder->get('blastType')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $blastType = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $blastType);
            }
        );
    }
}
