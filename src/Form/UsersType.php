<?php

namespace App\Form;

use App\Entity\Users;
use Doctrine\DBAL\Types\StringType;
use http\Client\Curl\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class,['label' => "Nom", "required" => false])
            ->add('firstname', TextType::class,['label' => "Prénom", "required" => false])
            ->add('email',EmailType::class, ['label' => "Adresse email"])
            ->add('password', PasswordType::class,['label' => "password","required" => false, "empty_data" => '$2y$13$029rQ3lnyigroXR2wsfdkevCrhjOPV.nd/rtLIoUf7ODQrd1dCGqe','constraints' => [
                new Length([
                    'min' => 8,
                    'minMessage' => 'Le mot de passe doit avoir au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
