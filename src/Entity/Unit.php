<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\Column(length: 10)]
    private ?string $unit_code;

    #[ORM\Column(length: 50)]
    private ?string $unit_name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $unit_desc = null;

    #[ORM\Column(name: 'unit_precision')]
    private ?int $unit_precision = 4;

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: Item::class)]
    private Collection $items;

    
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getUnitName(): ?string
    {
        return $this->unit_name;
    }

    public function setUnitName(string $unit_name): static
    {
        $this->unit_name = $unit_name;

        return $this;
    }

    public function getUnitDesc(): ?string
    {
        return $this->unit_desc;
    }

    public function setUnitDesc(?string $unit_desc): static
    {
        $this->unit_desc = $unit_desc;

        return $this;
    }

    public function getUnitCode(): ?string
    {
        return $this->unit_code;
    }

    public function setUnitCode(string $unit_code): static
    {
        $this->unit_code = $unit_code;

        return $this;
    }

    public function getUnitPrecision(): ?int
    {
        return $this->unit_precision;
    }

    public function setUnitPrecision(int $unit_precision): static
    {
        $this->unit_precision = $unit_precision;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setItemUnit($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getItemUnit() === $this) {
                $item->setItemUnit(null);
            }
        }

        return $this;
    }
}
