<?php

/**
 * Tag Service.
 */
namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService
 */
class TagService
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * TagService constructor.
     *
     * @param TagRepository      $tagRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param $page
     *
     * @return PaginationInterface
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEM_PER_PAGE
        );
    }

    /**
     * @param Tag $tag
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * @param Tag $tag
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }
    /**
     * @param string $title
     *
     * @return Tag|null
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }



}