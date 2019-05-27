<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('img', FileType::class, [
              'required' => false
            ])
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('isRecommended',
            CheckboxType::class,[
              'required' => false,
            ])
            ->add('isNew',
            CheckboxType::class, [
              'required' => false,
            ])
            ->add('category', EntityType::class, [
              'class' => Category::class,
              'choice_label' => 'name',
            ])
            ->add('shop', EntityType::class, [
              'class' => Shop::class,
              'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
