<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img_original', FileType::class, [
                'label' => 'image',
                'data_class' => null,
                'required' => empty($options['data']?->getImgOriginal()),
            ])
            ->add('article_title', TextType::class, ['label' => 'name'])
            ->add('article_url', TextType::class, ['label' => 'slug'])
            ->add('intro', TextareaType::class, [
                'label' => 'intro',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'content',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'published',
                'required' => false,
            ])
            ->add('on_first_page', CheckboxType::class, [
                'label' => 'on_first_page',
                'required' => false,
            ])
            ->add('has_full_content', CheckboxType::class, [
                'label' => 'has_full_content',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'category',
                'placeholder' => 'select',
                'class' => Category::class,
                'choice_label' => 'category_name',
            ])
            ->add('date', DateType::class, [
                'label' => 'date',
                'widget' => 'single_text'
            ])
            ->add('article_description', TextareaType::class, ['label' => 'description'])
            ->add('article_keywords', TextareaType::class, ['label' => 'keywords'])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'tag',
                'multiple' => true,
                'label' => 'tags',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
