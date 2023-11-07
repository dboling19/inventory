<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
  #[ORM\Id]
  #[ORM\Column(type:'string', length:8, nullable:false)]
  private ?string $whs_code;

  #[ORM\Column(type:'string', length:255, nullable:false)]
  private ?string $whs_desc;

  #[ORM\Column(type:'string', length:255, nullable:true)]
  private ?string $whs_addr;

  #[ORM\Column(type: Types::TEXT, nullable:true)]
  private ?string $whs_notes;

  #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:'warehouse', orphanRemoval:true, cascade:['persist'])]
  #[ORM\InverseJoinColumn(name:'item_code', referencedColumnName:'item_code')]
  #[ORM\InverseJoinColumn(name:'loc_code', referencedColumnName:'loc_code')]
  #[ORM\InverseJoinColumn(name:'whs_code', referencedColumnName:'whs_code')]
  private $item_loc;

  public function __construct()
  {
    $this->item_loc = new ArrayCollection();
  }

  public function getWhsCode(): ?string
  {
    return $this->whs_code;
  }

  public function setWhsCode(string $whs_code): static
  {
    $this->whs_code = $whs_code;

    return $this;
  }

  public function getWhsDesc(): ?string
  {
    return $this->whs_desc;
  }

  public function setWhsDesc(string $whs_desc): static
  {
    $this->whs_desc = $whs_desc;

    return $this;
  }

  public function getWhsAddr(): ?string
  {
    return $this->whs_addr;
  }

  public function setWhsAddr(?string $whs_addr): static
  {
    $this->whs_addr = $whs_addr;

    return $this;
  }

  public function getWhsNotes(): ?string
  {
    return $this->whs_notes;
  }

  public function setWhsNotes(?string $whs_notes): static
  {
    $this->whs_notes = $whs_notes;

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
      $itemLoc->setWarehouse($this);
    }

    return $this;
  }

  public function removeItemLoc(ItemLocation $itemLoc): static
  {
    if ($this->item_loc->removeElement($itemLoc)) {
      // set the owning side to null (unless already changed)
      if ($itemLoc->getWarehouse() === $this) {
        $itemLoc->setWarehouse(null);
      }
    }

    return $this;
  }
}
