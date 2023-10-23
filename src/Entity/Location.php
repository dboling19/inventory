<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass:LocationRepository::class)]
class Location implements JsonSerializable
{
  #[ORM\Id]
  #[ORM\Column(type:'string', length:255, nullable:false)]
  private ?string $loc_name;

  #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:'location', orphanRemoval:true, cascade:['persist'])]
  #[ORM\InverseJoinColumn(name:'item', referencedColumnName:'item')]
  #[ORM\InverseJoinColumn(name:'location', referencedColumnName:'location')]
  private $itemlocation;

  #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:'location', cascade:['persist', 'remove'])]
  #[ORM\InverseJoinColumn(name:'item', referencedColumnName:'item')]  
  #[ORM\InverseJoinColumn(name:'location', referencedColumnName:'location')]
  private $transaction;

  private $item_quantity;


  public function __construct()
  {      
    $this->itemlocation = new ArrayCollection();
    $this->transaction = new ArrayCollection();
  }

  public function jsonSerialize()
  {
    return [
      'loc_name' => $this->loc_name,
      'item_quantity' => $this->item_quantity,
    ];
  }

  public function getLocName(): ?string
  {
    return $this->loc_name;
  }

  public function setLocName(string $loc_name): static
  {
    $this->loc_name = $loc_name;

    return $this;
  }

  /**
   * @return Collection<int, ItemLocation>
   */
  public function getItemlocation(): Collection
  {
    return $this->itemlocation;
  }

  public function addItemlocation(ItemLocation $itemlocation): static
  {
    if (!$this->itemlocation->contains($itemlocation)) {
      $this->itemlocation->add($itemlocation);
      $itemlocation->setLocation($this);
    }

    return $this;
  }

  public function removeItemlocation(ItemLocation $itemlocation): static
  {
    if ($this->itemlocation->removeElement($itemlocation)) {
      // set the owning side to null (unless already changed)
      if ($itemlocation->getLocation() === $this) {
        $itemlocation->setLocation(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Transaction>
   */
  public function getTransaction(): Collection
  {
    return $this->transaction;
  }

  public function addTransaction(Transaction $transaction): static
  {
    if (!$this->transaction->contains($transaction)) {
      $this->transaction->add($transaction);
      $transaction->setLocation($this);
    }

    return $this;
  }

  public function removeTransaction(Transaction $transaction): static
  {
    if ($this->transaction->removeElement($transaction)) {
      // set the owning side to null (unless already changed)
      if ($transaction->getLocation() === $this) {
        $transaction->setLocation(null);
      }
    }

    return $this;
  }

  public function getItemQuantity(): ?int
  {
    $item_quantity = 0;
    foreach ($this->itemlocation as $itemlocation)
    {
      $item_quantity += $itemlocation->getQuantity();
    }
    return $item_quantity;
  }
}
