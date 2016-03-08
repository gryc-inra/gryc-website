<?php

namespace Grycii\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de réponse à un message reçu.
 * Ici, le formulaire n'est pas mappé à une entitée, donc on y déclare pas une fonction configureOptions, et on doit
 * y placer les contraintes de validation directement.
 */
class ContactUsReplyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', TextareaType::class, array(
                'attr' => array(
                    'rows' => 20,
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 30)),
                ),
            ))
            ->add('reply', SubmitType::class);
    }
}
