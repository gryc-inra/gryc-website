<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('category', ChoiceType::class, [
                'placeholder' => 'Choose a category',
                'choices' => [
                    'Account' => 'Account',
                    'Bug report' => 'Bug report',
                    'Informations request'=> 'Informations request',
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
