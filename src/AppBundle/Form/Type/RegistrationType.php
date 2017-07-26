<?php

namespace AppBundle\Form\Type;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'checkMX' => true,
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'data-help' => '
                            <div class="row password-control">
                                <div class="col-sm-6">
                                    <span id="number-chars" class="fa fa-times" style="color:#FF0004;"></span> 8 Characters Long<br>
                                    <span id="upper-case" class="fa fa-times" style="color:#FF0004;"></span> One Uppercase Letter
                                </div>
                                <div class="col-sm-6">
                                    <span id="lower-case" class="fa fa-times" style="color:#FF0004;"></span> One Lowercase Letter<br>
                                    <span id="number" class="fa fa-times" style="color:#FF0004;"></span> One Number
                                </div>
                            </div>
                        ',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'data-help' => '
                            <div class="row password-control">
                                <div class="col-sm-12">
                                    <span id="password-match" class="fa fa-times" style="color:#FF0004;"></span> Passwords Match
                                </div>
                            </div>
                        ',
                    ],
                ],
            ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('company', TextType::class)
            ->add('recaptcha', EWZRecaptchaType::class, [
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue(),
                ],
                'attr' => [
                    'options' => [
                        'theme' => 'light',
                        'type'  => 'image',
                        'size' => 'invisible',
                        'defer' => true,
                        'async' => true,
                        'bind' => 'registration_submit', // this is the id of the form submit button
                    ],
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }
}
