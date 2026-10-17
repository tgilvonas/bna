<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category_name', TextType::class, ['label' => 'name'])
            ->add('category_url', TextType::class, ['label' => 'slug'])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'active_female',
            ])
            ->add('category_description', TextareaType::class, [
                'required' => false,
                'label' => 'description'
            ])
            ->add('category_keywords', TextareaType::class, [
                'required' => false,
                'label' => 'keywords'
            ])
            ->add('ord', NumberType::class, ['label' => 'ord'])
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
