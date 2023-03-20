<?php
// src/Form/CompleteProfilFormType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CompleteProfilRecruteurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomentreprise', TextType::class, [
                'label' => "Nom de l'entreprise",
                'attr' => ['maxlength' => 50],
                'required' => false,
            ])
            ->add('adresseentreprise', TextType::class, [
                'label' => "Adresse de l'entreprise",
                'attr' => ['maxlength' => 255],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['maxlength' => 255],
                'required' => false,
                'constraints' => [
                    new Email(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['maxlength' => 255],
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'Le mot de passe doit comporter au moins 6 caractères',
                        'maxMessage' => 'Le mot de passe ne doit pas dépasser 255 caractères',
                    ])],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider les changements'])
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