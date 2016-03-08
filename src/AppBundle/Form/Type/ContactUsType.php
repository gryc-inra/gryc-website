<?php

namespace Grycii\AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

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
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:ContactUsCategory',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choose a category',
            ))
            ->add('subject')
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'rows' => 20,
                ), ))
            ->add('recaptcha', EWZRecaptchaType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new RecaptchaTrue(),
                ), ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grycii\AppBundle\Entity\ContactUs',
        ));
    }
}
