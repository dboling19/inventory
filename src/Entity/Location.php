<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass:LocationRepository::class)]
class Location implements JsonSerializable
{
  #[ORM\Id]
  #[ORM\Column(type:'string', length:8, nullable:false)]
  private string $loc_code;

  #[ORM\Column(type:'string', length:255, nullable:false)]
  private string $loc_name;

  #[ORM\Column(type:Types::TEXT, nullable:true)]
  private ?string $loc_desc;

  #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:'location', orphanRemoval:true, cascade:['persist'])]
  #[ORM\InverseJoinColumn(name:'item_code', referencedColumnName:'item_code')]
  #[ORM\InverseJoinColumn(name:'loc_code', referencedColumnName:'loc_code')]
  #[ORM\InverseJoinColumn(name:'whs_code', referencedColumnName:'whs_code')]
  private $item_loc;

  #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:'location', cascade:['persist', 'remove'])]
  #[ORM\InverseJoinColumn(name:'item_code', referencedColumnName:'item_code')]  
  #[ORM\InverseJoinColumn(name:'loc_code', referencedColumnName:'loc_code')]
  private $loc_trans;

  private $item_qty;

  private Collection $warehouses;

  private Collection $items;


  public function __construct()
  {      
    $this->item_loc = new ArrayCollection();
    $this->loc_trans = new ArrayCollection();
  }

  public function jsonSerialize()
  {
    return [
      'loc_name' => $this->loc_name,
      'item_qty' => $this->item_qty,
    ];
  }

  public function getLocCode(): ?string
  {
      return $this->loc_code;
  }

  public function setLocCode(string $loc_code): static
  {
    $this->loc_code = strtoupper($loc_code);

    return $this;
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
  public function getItemLoc(): Collection
  {
      return $this->item_loc;
  }

  public function addItemLoc(ItemLocation $itemLoc): static
  {
      if (!$this->item_loc->contains($itemLoc)) {
          $this->item_loc->add($itemLoc);
          $itemLoc->setLocation($this);
      }

      return $this;
  }

  public function removeItemLoc(ItemLocation $itemLoc): static
  {
      if ($this->item_loc->removeElement($itemLoc)) {
          // set the owning side to null (unless already changed)
          if ($itemLoc->getLocation() === $this) {
              $itemLoc->setLocation(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Transaction>
   */
  public function getLocTrans(): Collection
  {
      return $this->loc_trans;
  }

  public function addLocTrans(Transaction $locTrans): static
  {
    if (!$this->loc_trans->contains($locTrans)) {
      $this->loc_trans->add($locTrans);
      $locTrans->setLocation($this);
    }

    return $this;
  }

  public function removeLocTrans(Transaction $locTrans): static
  {
    if ($this->loc_trans->removeElement($locTrans)) {
      // set the owning side to null (unless already changed)
      if ($locTrans->getLocation() === $this) {
        $locTrans->setLocation(null);
      }
    }

    return $this;
  }

  public function getItemQty(): ?int
  {
    $item_qty = 0;
    foreach ($this->item_loc as $item_loc)
    {
      $item_qty += $item_loc->getQuantity();
    }
    return $item_qty;
  }

  /**
   * @return Collection<int, Warehouse>
   */
  public function getWarehouses(): Collection
  {
    $this->warehouses = new ArrayCollection;
    foreach ($this->item_loc as $item_loc)
    {
      $this->warehouses->add($item_loc->getWarehouse());
    }

    return $this->warehouses;
  }

  /**
   * @return Collection<int, Item>
   */
  public function getItems(): Collection
  {
    $this->items = new ArrayCollection;
    foreach ($this->item_loc as $item_loc)
    {
      $this->items->add($item_loc->getItem());
    }

    return $this->items;
  }

  public function getLocDesc(): ?string
  {
      return $this->loc_desc;
  }

  public function setLocDesc(string $loc_desc): static
  {
    $this->loc_desc = $loc_desc;

    return $this;
  }

}
