<?php
/**
 * Category type.
 */
namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryType
 */
class CategoryType extends AbstractType
{

    /**
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label_name',
                'required' => true,
                'attr' => ['max_length' => 50],
            ]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver tor the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data class' => Category::class]);
    }

    /**
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'category';
    }

}

