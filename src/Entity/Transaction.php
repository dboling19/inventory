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
  private ?int $trans_num;

  #[ORM\ManyToOne(targetEntity:Item::class, inversedBy:'trans')]
  #[ORM\JoinColumn(nullable:false, name:'name', referencedColumnName:'item_code')]
  private ?Item $item;

  #[ORM\ManyToOne(targetEntity:Location::class, inversedBy:'trans')]
  #[ORM\JoinColumn(nullable:false, name:'name', referencedColumnName:'loc_code')]
  private ?Location $loc;

  #[ORM\Column(type:'string', nullable:false)]
  private ?string $trans_qty_change;

  #[ORM\Column(type:'datetime', nullable:false)]
  private ?DateTimeInterface $trans_datetime;

  
  public function getTransQtyChange(): ?string
  {
      return $this->trans_qty_change;
  }

  public function setTransQtyChange(string $trans_qty_change): static
  {
      $this->trans_qty_change = $trans_qty_change;

      return $this;
  }

  public function getTransDatetime(): ?\DateTimeInterface
  {
      return $this->trans_datetime;
  }

  public function setTransDatetime(\DateTimeInterface $trans_datetime): static
  {
      $this->trans_datetime = $trans_datetime;

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
      return $this->loc;
  }

  public function setLocation(?Location $loc): static
  {
      $this->loc = $loc;

      return $this;
  }
}
