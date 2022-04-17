<?php

namespace App\Repository;

use App\DTO\AuthorSearchCriteria;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Book $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByPriceBetween(float $minimum, float $maximum): array
    {
        return $this->createQueryBuilder('book')
            ->andWhere('book.price >= :min')
            ->andWhere('book.price <= :max')
            ->setParameter('min', $minimum)
            ->setParameter('max', $maximum)
            ->getQuery()
            ->getResult();
    }

    public function findByPriceBetweenOrder(float $minimum, float $maximum): array
    {
        return $this->createQueryBuilder('book')
            ->andWhere('book.price >= :min')
            ->andWhere('book.price <= :max')
            ->setParameter('min', $minimum)
            ->setParameter('max', $maximum)
            ->orderBy('book.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByAuthorName(string $authorName): array
    {
        return $this->createQueryBuilder('book')
            ->leftjoin('book.author', 'author')
            ->andWhere('author.name LIKE :authorName')
            ->setParameter('authorName', $authorName)
            ->orderBy('book.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('book')
            ->leftjoin('book.categories', 'category')
            ->andWhere('category.title LIKE :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

}
