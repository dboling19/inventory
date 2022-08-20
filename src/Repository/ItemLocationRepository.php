<?php

namespace App\Repository;

use App\Entity\ItemLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemLocation>
 *
 * @method ItemLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemLocation[]    findAll()
 * @method ItemLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemLocation::class);
    }

    /**
     * @author Daniel Boling
     * @return Item[] Returns an array of Item objects
     */
    public function findItem($item)
    {
        return $this->createQueryBuilder('il')
            ->andWhere('item.name like :val')
            ->leftJoin('il.item', 'item')
            ->setParameter('val', '%'.$item.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ItemLocation $entity, bool $flush = true): void
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
    public function remove(ItemLocation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ItemLocation[] Returns an array of ItemLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemLocation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
