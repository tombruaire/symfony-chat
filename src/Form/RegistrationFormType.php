<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => "off",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom d'utilisateur !",
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => "Le nom d'utilisateur ne doit pas contenir d'espaces ni de caractères spéciaux !.",
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Adresse email",
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => "off",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir une adresse email !",
                    ]),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'invalid_message' => 'Les mots de passes ne correspondent pas !',
                'options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'onclick' => 'afficherBoxPatternMdp()',
                        // 'onblur' => 'masquerBoxPatternMdp()',
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe !',
                    ]),
                    new Regex([
                        'pattern' => '/^.{8,}$/',
                        'message' => 'Votre mot de passe doit contenir 8 caractères',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Votre mot de passe doit contenir une lettre majuscule',
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Votre mot de passe doit contenir une lettre minuscule',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Votre mot de passe doit contenir un chiffre',
                    ]),
                    new Regex([
                        'pattern' => '/[@#$%;,!&%?()]/',
                        'message' => 'Votre mot de passe doit contenir un caractère spécial',
                    ]),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
