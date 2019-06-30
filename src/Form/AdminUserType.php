<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\UserType;
use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

/**
* AdminUserType class
*/
class AdminUserType extends UserType
{
    /**
    * @inheritdoc
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      parent::buildForm($builder, $options);
      $builder->add('isAdmin', CheckboxType::class, ['mapped' => false]);
    }

    /**
    * @inheritdoc
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration'],
        ]);
    }
}
