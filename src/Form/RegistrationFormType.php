<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('secondName', TextType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('plainConfirmPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Зарегистрироваться',
                'attr' => [
                    'class' => 'btn',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'form', 'novalidate' => 'novalidate'],
        ]);
    }
}
