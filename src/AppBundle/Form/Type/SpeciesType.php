<?php

namespace AppBundle\Form\Type;

use AppBundle\Repository\CladeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxId', IntegerType::class, [
                'label' => 'TaxId',
                'attr' => [
                    'data-help' => 'The taxID of the species, you can find it <a target="_blank" href="https://www.ncbi.nlm.nih.gov/taxonomy">here</a>.',
                ],
                'required' => false,
            ])
            ->add('clade', EntityType::class, [
                'class' => 'AppBundle:Clade',
                'choice_label' => 'name',
                'query_builder' => function (CladeRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'placeholder' => '-- select a clade --',
            ])
            ->add('scientificName', TextType::class)
            ->add('genus', TextType::class)
            ->add('species', TextType::class)
            ->add('geneticCode', IntegerType::class)
            ->add('mitoCode', IntegerType::class)
            ->add('lineages', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('synonyms', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Species',
        ]);
    }
}
