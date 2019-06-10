<?php

namespace App\Form;

use App\Entity\Disposal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DisposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
              'choices' => [
                Disposal::STATUS_WAITING_FOR_PAYMENT => Disposal::STATUS_WAITING_FOR_PAYMENT,
                DISPOSAL::STATUS_PAYED => DISPOSAL::STATUS_PAYED,
                DISPOSAL::STATUS_SENDED => DISPOSAL::STATUS_SENDED
              ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Disposal::class,
        ]);
    }
}
