<?php

namespace AppBundle\Form\Type;

use AppBundle\Utils\CartManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MultipleAlignmentType extends AbstractType
{
    private $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(?:>[\w-]+\R(?:[A-Z]+\R?)+\R*){2,}$/i',
                        'message' => 'This is not a valid FASTA.',
                    ]),
                ],
                'attr' => [
                    'rows' => 10,
                ],
                'label' => 'Fasta',
            ])
            ->add('cart', CartType::class, [
                'mapped' => false,
                'label' => 'Cart parameters',
            ])
            ->add('align', SubmitType::class, ['label' => 'Align'])
            ->add('alignFromCart', SubmitType::class, ['label' => 'Align from cart'])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();

                // If the user have clicked on Align from cart
                if (isset($data['alignFromCart'])) {
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\MultipleAlignment',
        ]);
    }
}
