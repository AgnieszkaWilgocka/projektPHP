<?php

/**
 * Record service
 */
namespace App\Service;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecordService
 *
 * @package App\Service
 */
class RecordService
{
    /**
     * @var RecordRepository
     */
    private $recordRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * RecordService constructor.
     *
     * @param RecordRepository   $recordRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(RecordRepository $recordRepository, PaginatorInterface $paginator)
    {
        $this->recordRepository = $recordRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recordRepository->queryAll(),
            $page,
            RecordRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
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
     * @param Record $record
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Record $record): void
    {
        $this->recordRepository->delete($record);
    }
}