<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            //->add('roles')
            ->add('password', PasswordType::class,['label' => "password","required" => false, 'mapped' => false,'constraints' => [
                new Length([
                    'min' => 8,
                    'minMessage' => 'Le mot de passe doit avoir au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]])
            ->add('firstname')
            ->add('lastname')
            //->add('checkBy')
            //->add('Validated')
            //->add('company')
            //->add('ValidatedBy')
            //->add('jobsPostulated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
