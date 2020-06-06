<?php
/**
 * Record type
 */
namespace App\Form;

use App\Entity\Category;
use App\Entity\Record;
use App\Entity\Tag;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecordType
 */
class RecordType extends AbstractType
{

    private $tagsDataTransformer;

    /**
     * RecordType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add(
               'title',
               TextType::class,
               [
                'label' => 'label_title',
                'required' => true,
                'attr' => ['max length' => 50],
                 ]
           );
        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => function (Category $category) {
                        return $category->getName();
                    },
                    'label' => 'label_category',
                    'placeholder' => 'choice_category',
                    'required' => true
                ]
            );
        $builder
            ->add(
                'tags',
                TextType::class,
                [
                    'label' => 'label_tags',
                    'required' => false,
                    'attr' => ['max_length' => 64],
                ]
            );
        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Record::class]);
    }


    /**
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'record';
    }
}
