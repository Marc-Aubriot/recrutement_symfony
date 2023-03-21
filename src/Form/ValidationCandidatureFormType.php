<?php
// src/Form/ValidationCandidatureFormType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ValidateCandidatureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkbox', CheckboxType::class, [
                'label' => "Valider la candidature",
                'required' => false,
                //'data' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => "Validation"])
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