<?php

namespace App\Form;

use App\Entity\Disposal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
* DisposalType
*/
class DisposalType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                Disposal::STATUS_WAITING_FOR_PAYMENT => Disposal::STATUS_WAITING_FOR_PAYMENT,
                DISPOSAL::STATUS_PAYED => DISPOSAL::STATUS_PAYED,
                DISPOSAL::STATUS_SENDED => DISPOSAL::STATUS_SENDED,
                ],
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Disposal::class,
        ]);
    }
}
