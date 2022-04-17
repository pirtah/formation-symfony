<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\ORM\ORMException;
use App\DTO\AuthorSearchCriteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Author $entity, bool $flush = true): void
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
    public function remove(Author $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('author')
            ->andWhere('author.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('author.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche tout les auteurs correspondant
     * aux critères de recherche
     */
    public function findByCriteria(AuthorSearchCriteria $criteria): array
    {
        $qb =  $this
            ->createQueryBuilder('author')
            ->setMaxResults($criteria->limit)
            ->setFirstResult($criteria->limit * ($criteria->page - 1))
            ->orderBy('author.' . $criteria->orderBy, $criteria->direction);

        if ($criteria->name) {
            $qb
                ->andWhere('author.name LIKE :name')
                ->setParameter('name', "%{$criteria->name}%");
        }

        if ($criteria->updatedAtStart) {
            $qb
                ->andWhere('author.updatedAt >= :updateDateStart')
                ->setParameter('updateDateStart', $criteria->updatedAtStart);
        }
        if ($criteria->updatedAtStop) {
            $qb
                ->andWhere('author.updatedAt <= :updateDateStop')
                ->setParameter('updateDateStop', $criteria->updatedAtStop);
        }
        return $qb->getQuery()->getResult();
    }
}
