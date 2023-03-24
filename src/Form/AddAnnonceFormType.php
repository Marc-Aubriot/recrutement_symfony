<?php
// src/Form/AddAnnonceFormType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddAnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitulé du poste recherché',
                'attr' => ['maxlength' => 255],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du poste recherché',
                'required' => true,
            ])
            ->add('horaires', TextType::class, [
                'label' => 'Horaires (exemple1: 35h, exemple2: 09h à 20h et 10h à 17h un jour sur deux',
                'attr' => ['maxlength' => 20],
                'required' => false,
            ])
            ->add('salaire', TextType::class, [
                'label' => 'Salaire (brut mensuel / annuel)',
                'attr' => ['maxlength' => 20],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Confirmer votre annonce'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'sanitize_html' => true,
        ]);
    }
}