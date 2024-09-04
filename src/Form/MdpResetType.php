<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MdpResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe *',
                ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe *'
                ],
                'invalid_message' => 'Les mots de passes ne correspondent pas !',
                'options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => "Cliquer ici pour afficher le pattern demandé",
                        'onclick' => 'afficherBoxPatternMdp()',
                        // 'onblur' => 'masquerBoxPatternMdp()',
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nouveau mot de passe !',
                    ]),
                    new Regex([
                        'pattern' => '/^.{8,}$/',
                        'message' => 'Votre nouveau mot de passe doit contenir 8 caractères',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Votre nouveau mot de passe doit contenir une lettre majuscule',
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Votre nouveau mot de passe doit contenir une lettre minuscule',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Votre nouveau mot de passe doit contenir un chiffre',
                    ]),
                    new Regex([
                        'pattern' => '/[@#$%;,!&%?()]/',
                        'message' => 'Votre nouveau mot de passe doit contenir un caractère spécial',
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
