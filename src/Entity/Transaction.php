<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:TransactionRepository::class)]
#[ORM\Table(name: "`transaction`")]
class Transaction
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $transaction_num;

  #[ORM\ManyToOne(targetEntity:Item::class, inversedBy:'transaction')]
  #[ORM\JoinColumn(nullable:false, name:'name', referencedColumnName:'name')]
  private ?Item $item;

  #[ORM\ManyToOne(targetEntity:Location::class, inversedBy:'transaction')]
  #[ORM\JoinColumn(nullable:false, name:'name', referencedColumnName:'name')]
  private ?Location $location;

  #[ORM\Column(type:'string', nullable:false)]
  private ?string $quantity_change;

  #[ORM\Column(type:'datetime', nullable:false)]
  private ?DateTimeInterface $datetime;

  
  public function getQuantityChange(): ?string
  {
      return $this->quantity_change;
  }

  public function setQuantityChange(string $quantity_change): static
  {
      $this->quantity_change = $quantity_change;

      return $this;
  }

  public function getDatetime(): ?\DateTimeInterface
  {
      return $this->datetime;
  }

  public function setDatetime(\DateTimeInterface $datetime): static
  {
      $this->datetime = $datetime;

      return $this;
  }

  public function getItem(): ?Item
  {
      return $this->item;
  }

  public function setItem(?Item $item): static
  {
      $this->item = $item;

      return $this;
  }

  public function getLocation(): ?Location
  {
      return $this->location;
  }

  public function setLocation(?Location $location): static
  {
      $this->location = $location;

      return $this;
  }
}
