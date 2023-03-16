<?php
// src/Form/AddConsultantFormType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddConsultantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email du Consultant',
                'attr' => ['maxlength' => 255],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe du Consultant',
                'attr' => ['maxlength' => 255],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'Le mot de passe doit comporter au moins 6 caractères',
                        'maxMessage' => 'Le mot de passe ne doit pas dépasser 255 caractères',
                    ])],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmation du mot de passe du Consultant',
                'attr' => ['maxlength' => 255],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'Le mot de passe doit comporter au moins 6 caractères',
                        'maxMessage' => 'Le mot de passe ne doit pas dépasser 255 caractères',
                    ])],
            ])
            ->add('connect', SubmitType::class, ['label' => 'Creer le compte "Consultant".'])
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