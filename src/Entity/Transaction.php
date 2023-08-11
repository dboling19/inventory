<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:TransactionRepository::class)]
class Transaction
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id;

  #[ORM\Column(type:'string', nullable:false)]
  private ?string $quantity_change;

  #[ORM\Column(type:'datetime', nullable:false)]
  private ?DateTimeInterface $datetime;

  #[ORM\ManyToOne(targetEntity:Item::class, inversedBy:'transaction')]
  #[ORM\JoinColumn(nullable:false)]
  private ?Item $item;

  #[ORM\ManyToOne(targetEntity:Location::class, inversedBy:'transaction')]
  #[ORM\JoinColumn(nullable:false)]
  private ?Location $location;


  public function getId(): ?int
  {
      return $this->id;
  }

  public function getQuantityChange(): ?string
  {
      return $this->quantity_change;
  }

  public function setQuantityChange(string $quantity_change): static
  {
      $this->quantity_change = $quantity_change;

      return $this;
  }

  public function getDatetime(): ?datetimeinterface
  {
      return $this->datetime;
  }

  public function setDatetime(datetimeinterface $datetime): static
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
