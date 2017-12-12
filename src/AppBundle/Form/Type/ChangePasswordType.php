<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'constraints' => new UserPassword(),
                'mapped' => false,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }
}
