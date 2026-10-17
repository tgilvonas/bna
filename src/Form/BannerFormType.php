<?php

namespace App\Form;

use App\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BannerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', FileType::class, [
                'label' => 'image',
                'data_class' => null,
                'required' => empty($options['data']?->getImg()),
            ])
            ->add('url', TextType::class, ['label' => 'url'])
            ->add('innew', CheckboxType::class, [
                'label' => 'in_new',
                'required' => false,
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'active_male',
                'required' => false,
            ])
            ->add('ord', NumberType::class, ['label' => 'ord'])
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Banner::class,
        ]);
    }
}
