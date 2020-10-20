<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'invalid_message' => 'Invalid username.',
            ])
            ->add('password', PasswordType::class, [
                'invalid_message' => 'Invalid password.',
            ])
            ->add('email', EmailType::class, [
                'invalid_message' => 'Invalid email.',
            ])
            ->add('firstname', TextType::class, [
                'invalid_message' => 'Invalid firstname.',
            ])
            ->add('lastname', TextType::class, [
                'invalid_message' => 'Invalid lastname.',
            ])
            ->add('phoneNumber', TextType::class, [
                'invalid_message' => 'Invalid phoneNumber.',
            ]);
    }
}
