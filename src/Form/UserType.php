<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                "label" => "Email",
                "attr" => [
                    "placeholder" => "Email de l'utilisateur"
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    "Utilisateur" => "ROLE_USER",
                    "Administrateur" => "ROLE_ADMIN"
                ],
                'multiple' => true,
                'expanded' => true,
            ]);

        // Using the form options to add a default parameter "edit" to false
        // Hides the password to the edit form of an User
        if(!$options["edit"]){
            $builder
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les deux mots de passes doivent être identiques",
                "first_options" => [
                    "label" => "Mot de passe",
                    "attr" => [
                        "placeholder" => "Mot de passe"
                    ],
                ],
                "second_options" => [
                    "label" => "Confimer le mot de passe",
                    "attr" => [
                        "placeholder" => "Confirmer le mot de passe"
                    ],
                    "constraints" => [
                        new NotBlank([
                            "message" => "Ce champ ne doit pas être vide.",
                        ]),
                    ],                        
                ],
            ]);
        }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "edit" => false
        ]);
    }
}
