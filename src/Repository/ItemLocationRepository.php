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
  public function findItem(array $params)
  {
    $qb = $this->createQueryBuilder('il')
      ->leftJoin('il.item', 'item')
      ->leftJoin('il.location', 'location')
    ;
    if (isset($params['item_name']))
    {
      $qb
        ->setParameter('item_name', '%'.$params['item_name'].'%')
        ->andWhere('item.name like :item_name')
      ;
    }
    if (isset($params['location']) && $params['location'] !== '')
    {
      $qb
        ->setParameter('loc_id', $params['location'])
        ->andWhere('location.id in (:loc_id)')
      ;
    }
    return $qb->getQuery();
  }


  /**
   * @author Daniel Boling
   * @return Quantity of items in specified location
   */
  public function getLocQty($loc)
  {
    return $this->createQueryBuilder('il')
      ->andWhere('il.location = :loc')
      ->setParameter('loc', $loc)
      ->select('SUM(il.quantity) as quantity')
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
