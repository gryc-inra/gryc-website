<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ContactUsType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'placeholder' => 'Choose a category',
                'choices' => [
                    'Account' => 'Account',
                    'Bug report' => 'Bug report',
                    'Informations request' => 'Informations request',
                    'Other (not listed)' => 'Other (not listed)',
                ],
            ])
            ->add('subject')
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => 20,
                ], ])
            ->add('recaptcha', EWZRecaptchaType::class, [
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue(),
                ], ]);

        $formModifier = function (FormInterface $form, $user = null) {
            $form
                ->add('firstName', TextType::class, [
                    'data' => is_a($user, User::class) ? $user->getFirstName() : null,
                ])
                ->add('lastName', TextType::class, [
                    'data' => is_a($user, User::class) ? $user->getLastName() : null,
                ])
                ->add('email', EmailType::class, [
                    'data' => is_a($user, User::class) ? $user->getEmail() : null,
                ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {
            $user = $this->tokenStorage->getToken()->getUser();
            $form = $event->getForm();

            $formModifier($form, $user);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ContactUs',
        ]);
    }
}
