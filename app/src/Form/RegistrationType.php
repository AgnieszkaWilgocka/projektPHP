<?php
/**
 * Registration form
 */
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 */
class RegistrationType extends AbstractType
{
    /**
     * Builds form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'required' => true,
            ]
        );

        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options' => array('label' => 'label_password'),
                'second_options' => array('label' => 'label_repeat_password'),
                'required' => true,
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
        $resolver->setDefaults(['data_class' => User::class]);
    }


    /**
     * Returns the prefix of the template block name for this type
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return 'user';
    }
}
