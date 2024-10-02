<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*$builder->add('username', TextType::class, [
            'attr' => [
                'minLenght' => 6,
                'maxLenght' => 20,
                'placeholder' => 'De 6 à 20 charactères',
            ]
        ])
        $builder
            ->add('avatar')*/
        $builder
            ->add('mail');

        $builder->add('password', PasswordType::class, [
            'hash_property_path' => 'password',
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
