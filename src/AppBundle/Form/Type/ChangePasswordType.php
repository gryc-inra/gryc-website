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
