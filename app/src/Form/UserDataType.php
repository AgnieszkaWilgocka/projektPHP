<?php

/**
 * UserData form
 */
namespace App\Form;

use App\Entity\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserDataType
 */
class UserDataType extends AbstractType
{
    /**
     * Builds form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'nick',
            TextType::class,
            [
                'label' => 'label_nick',
                'required' => true,
                'attr' => ['max length' => 45],
            ]
        );
    }

    /**
     * Configure the options for this type
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => UserData::class]);
    }

    /**
     * Returns the prefix of the template block name for this type
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return 'userData';
    }
}
