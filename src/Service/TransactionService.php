<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Transaction;
use Datetime;
use Datetimezone;


class TransactionService
{
  public function __construct(
    private EntityManagerInterface $em,
  ) { }


  public function create_transaction(?Item $item, ?Location $location, ?int $quantity_change): void
  {
    $transaction = new Transaction;
    $transaction->setItem($item);
    $transaction->setLocation($location);
    $transaction->setQuantityChange($quantity_change);
    $transaction->setDatetime(new datetime('now', new datetimezone('America/Indiana/Indianapolis')));
    $this->em->persist($transaction);
  }
}