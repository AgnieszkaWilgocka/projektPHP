<?php

namespace App\Repository;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Borrowing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrowing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrowing[]    findAll()
 * @method Borrowing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowingRepository extends ServiceEntityRepository
{
    const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * BorrowingRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    /**
     * @param Borrowing $borrowing
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Borrowing $borrowing)
    {
        $this->_em->persist($borrowing);
        $this->_em->flush($borrowing);
    }

    /**
     * @param Borrowing $borrowing
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Borrowing $borrowing)
    {
        $this->_em->remove($borrowing);
        $this->_em->flush($borrowing);
    }

    /**
     * @param UserInterface $user
     *
     * @return QueryBuilder
     */
    public function queryByAuthor(UserInterface $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('borrowing.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }
    /**
     * @return QueryBuilder
     */
    public function queryAll()
    {
        return $this->getOrCreateQueryBuilder()
        ->select('borrowing', 'record')
            ->innerJoin('borrowing.record', 'record')
        ->orderBy('borrowing.createdAt', 'DESC');
    }




    /**
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('borrowing');
    }
    // /**
    //  * @return Borrowing[] Returns an array of Borrowing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Borrowing
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
