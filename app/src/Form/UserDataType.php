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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'login',
            TextType::class,
            [
                'required' => true,
                'attr' => ['max length' => 45],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => UserData::class]);
    }

    public function getBlockPrefix()
    {
        return 'userData';
    }
}
