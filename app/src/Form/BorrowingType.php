<?php
/**
 * Borrowing form
 */
namespace App\Form;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BorrowingType
 */
class BorrowingType extends AbstractType
{
    private $recordRepository;

    /**
     * BorrowingType constructor.
     *
     * @param RecordRepository $recordRepository
     */
    public function __construct(RecordRepository $recordRepository)
    {
        $this->recordRepository = $recordRepository;
    }

    /**
     * Builds the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'record',
            EntityType::class,
            [
                'class' => Record::class,
                [
                'choices' => $this->recordRepository->queryAvailableRecord(),
                ],
                'label' => 'label_records',
                'required' => true,
                'placeholder' => 'choice_record',
            ]
        );
        $builder->add(
            'comment',
            TextType::class,
            [
                'label' => 'label_comment',
                'attr' => ['max_length' => 50],
            ]
        );
    }

    /**
     * Configures the options for this type
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([BorrowingType::class]);
    }

    /**
     * Returns the prefix of the template block name for this type
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'borrowing';
    }
}
