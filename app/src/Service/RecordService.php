<?php

/**
 * Record service
 */
namespace App\Service;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecordService
 */
class RecordService
{
    /**
     * Record repository
     *
     * @var RecordRepository
     */
    private $recordRepository;

    /**
     * Category service
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Tag service
     *
     * @var TagService
     */
    private $tagService;

    /**
     * Paginator
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * RecordService constructor.
     *
     * @param RecordRepository   $recordRepository
     * @param PaginatorInterface $paginator
     * @param CategoryService    $categoryService
     * @param TagService         $tagService
     */
    public function __construct(RecordRepository $recordRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->recordRepository = $recordRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->recordRepository->queryAll($filters),
            $page,
            RecordRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Query builder for all records
     *
     * @return QueryBuilder
     */
    public function getAllRecords(): QueryBuilder
    {
        return $this->recordRepository->getAllRecords();
    }
    /**
     * Query builder for only available records
     *
     * @return QueryBuilder
     */
    public function getAvailableRecords():QueryBuilder
    {
        return $this->recordRepository->getAvailableRecords();
    }
    /**
     * Save record
     *
     * @param Record $record
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Record $record): void
    {
        $this->recordRepository->save($record);
    }

    /**
     * Delete record
     *
     * @param Record $record
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Record $record): void
    {
        $this->recordRepository->delete($record);
    }

    /**
     * Prepare filters for the records list
     *
     * @param array $filters
     *
     * @return array
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];

        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById(
                $filters['tag_id']
            );

            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
