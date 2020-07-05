<?php
/**
 * Borrowing form
 */
namespace App\Form;

use App\Entity\Record;
use App\Repository\RecordRepository;
use App\Service\RecordService;
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

    /**
     * Record service
     *
     * @var RecordService
     */
    private $recordService;

    /**
     * BorrowingType constructor.
     *
     * @param RecordService $recordService
     */
    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
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
                'choice_label' => 'title',
                'query_builder' => $options["show_all_records"] ? $this->recordService->getAllRecords() : $this->recordService->getAvailableRecords(),
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
        $resolver->setRequired("show_all_records");
        $resolver->setAllowedTypes("show_all_records", "bool");
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
