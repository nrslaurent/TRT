<?php

namespace App\Form;

use App\Entity\Jobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Intitulé'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('hours', TextType::class, ['label' => 'Horaires'])
            ->add('salary', TextType::class, ['label' => 'Salaire'])
            //->add('validated')
            //->add('published')
            //->add('createdBy')
            //->add('checkedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jobs::class,
        ]);
    }
}
