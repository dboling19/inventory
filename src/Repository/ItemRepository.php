<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Item::class);
  }

  public function findByLoc(string $loc_code)
  {
    return $this->createQueryBuilder('i')
      ->join('i.item_loc', 'item_loc')
      ->join('item_loc.location', 'loc')
      ->setParameters(['loc_code' => $loc_code])
      ->andWhere('loc.loc_code like :loc_code')
      ->getQuery()
    ;
  }

  /**
   * @author Daniel Boling
   * @return Item[] Returns an array of Item objects
   */
  public function filter(array $params)
  {
    $qb = $this->createQueryBuilder('i')
      ->leftJoin('i.location', 'location')
      ->addSelect('location')
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
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function add(Item $entity, bool $flush = true): void
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
  public function remove(Item $entity, bool $flush = true): void
  {
    $this->_em->remove($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }

  // /**
  //  * @return Item[] Returns an array of Item objects
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
  public function findOneBySomeField($value): ?Item
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
