<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:TransactionRepository::class)]
class Transaction
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private $id;

  #[ORM\Column]
  private $quantity_change;

  #[ORM\Column(type:'date')]
  private $date;

  #[ORM\ManyToOne(targetEntity:Item::class, inversedBy:'transaction')]
  #[ORM\JoinColumn(nullable:false)]
  private $item;

  public function getId(): ?int
  {
      return $this->id;
  }

  public function getQuantityChange(): ?string
  {
      return $this->quantity_change;
  }

  public function setQuantityChange(?string $quantity_change): self
  {
      $this->quantity_change = $quantity_change;

      return $this;
  }

  public function getDate(): ?\DateTimeInterface
  {
      return $this->date;
  }

  public function setDate(\DateTimeInterface $date): self
  {
      $this->date = $date;

      return $this;
  }

  public function getItem(): ?Item
  {
      return $this->item;
  }

  public function setItem(?Item $item): self
  {
      $this->item = $item;

      return $this;
  }
}
