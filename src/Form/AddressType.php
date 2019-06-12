<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

/**
* AddressType class
*/
class AddressType extends AbstractType
{
    /**
    * @inheritdoc
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', TextType::class, [
              'constraints' => [
                new Assert\NotBlank(),
                new Assert\Regex("/^[\p{L}_\d \n]+$/"),
                new Assert\Length(['min' => 1, 'max' => 80]),
              ],
        ]);
    }
}
