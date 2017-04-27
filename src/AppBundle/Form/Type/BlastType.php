<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlastType extends AbstractType
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
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
            ->add('strains', EntityType::class, [
                'class' => 'AppBundle\Entity\Strain',
                'choice_value' => 'id',
                'choice_label' => 'name',
                'group_by' => function ($strain) {
                    return $strain->getSpecies()->getScientificName();
                },
                'multiple' => true,
            ])
            ->add('query', TextareaType::class, [
                'constraints' => [
                  new NotBlank(),
                ],
                'attr' => [
                    'rows' => 10,
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
            ->add('maxHits', IntegerType::class)
            ->add('otherOptions', TextType::class, [
                'required' => false,
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
                    'CDS (nucleotides)' => 'cds_nuc',
                    'Chromosomes' => 'chromosomes',
                ];

                $isMatrixDisabled = true;
            }

            $form->add('database', ChoiceType::class, [
                'choices' => $databaseChoices,
            ]);

            $form->add('matrix', ChoiceType::class, [
                'choices' => [
                    'PAM30' => 'PAM30',
                    'PAM70' => 'PAM70',
                    'PAM250' => 'PAM250',
                    'BLOSUM45' => 'BLOSUM45',
                    'BLOSUM50' => 'BLOSUM50',
                    'BLOSUM62' => 'BLOSUM62',
                    'BLOSUM80' => 'BLOSUM80',
                    'BLOSUM90' => 'BLOSUM90',
                ],
                'disabled' => $isMatrixDisabled,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $data = $event->getData();

                if (null === $data) {
                    $event->setData([
                        'blastType' => 'blastp',
                        'database' => 'cds_prot',
                        'filter' => false,
                        'evalue' => 0.001,
                        'gapped' => true,
                        'maxHits' => 100,
                        'matrix' => 'BLOSUM62',
                    ]);
                }

                $formModifier($form, $data['blastType']);
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
