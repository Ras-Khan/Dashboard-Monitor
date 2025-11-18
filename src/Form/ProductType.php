<?php

namespace App\Form;

use App\Model\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => ['placeholder' => 'e.g., Amzfast Monitor Curvo', 'class' => 'p-2 border rounded-md w-full']
            ])
            ->add('url', UrlType::class, [
                'label' => 'Product URL',
                'attr' => ['placeholder' => 'e.g., https://www.amazon.nl/...', 'class' => 'p-2 border rounded-md w-full'],
                'required' => false,
            ])
            ->add('currentPrice', NumberType::class, [
                'label' => 'Starting Price (€)',
                'scale' => 2,
                'attr' => ['placeholder' => 'e.g., 99.99', 'class' => 'p-2 border rounded-md w-full'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            // Disable CSRF protection for simplicity 
            'csrf_protection' => true,
        ]);
    }
}