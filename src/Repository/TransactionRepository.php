<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Transaction::class);
  }

  /**
   * @author Daniel Boling
   * @return Item[] Returns an array of Item objects
   */
  public function filter(array $params)
  {
    $qb = $this->createQueryBuilder('t')
      ->leftJoin('t.item', 'item')
      ->leftJoin('t.location', 'location')
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
    if (isset($params['min_date']) && $params['min_date'] !== '')
    {
      $qb
        ->setParameter('min_date', $params['min_date'])
        ->andWhere('datetime >= :min_date')
      ;
    }
    if (isset($params['max_date']) && $params['max_date'] !== '')
    {
      $qb
        ->setParameter('max_date', $params['max_date'])
        ->andWhere('datetime <= :max_date')
      ;
    }
    return $qb->getQuery();
  }

  /**
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function add(Transaction $entity, bool $flush = true): void
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
  public function remove(Transaction $entity, bool $flush = true): void
  {
    $this->_em->remove($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }

  // /**
  //  * @return Transaction[] Returns an array of Transaction objects
  //  */
  /*
  public function findByExampleField($value)
  {
    return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('t.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
    ;
  }
  */

  /*
  public function findOneBySomeField($value): ?Transaction
  {
    return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
    ;
  }
  */
}
