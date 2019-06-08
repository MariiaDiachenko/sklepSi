<?php

namespace App\Form;

use App\Entity\Disposal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address')
            ->add('status')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('shop')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Disposal::class,
        ]);
    }
}
