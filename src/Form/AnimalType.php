<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Animal;
use App\Entity\AnimalCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form__control form__input',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form__control form__textarea',
                ]])
            ->add('category', EntityType::class, [
                'class' => AnimalCategory::class,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form__control form__select',
                ],
            ])
            ->add('uploadedImage', FileType::class, [
                'attr' => [
                    'class' => 'form__control',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить запись',
                'attr' => [
                    'class' => 'btn',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
            'attr' => ['class' => 'form', 'novalidate' => 'novalidate'],
        ]);
    }
}
