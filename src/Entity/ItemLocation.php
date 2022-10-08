<?php

namespace App\Entity;

use App\Repository\ItemLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\TransactionRepository;
use App\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemLocationRepository::class)
 */
class ItemLocation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="itemlocation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="itemlocation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->getItem()->getName();
    }

    public function setItemName(?string $name): self
    {
        $this->getItem()->setName($name);

        return $this;
    }

    public function getItemDescription(): ?string
    {
        return $this->getItem()->getDescription();
    }

    public function setItemDescription(?string $description): self
    {
        $this->getItem()->setDescription($description);

        return $this;
    }

    public function setQuantityChange(?string $change): self
    {
        $this->trans = new Transaction();
        $this->getItem()->addTransaction($this->trans);
        $this->trans->setDate(new \DateTime());
        $this->trans->setQuantityChange($change);

        return $this;
    }

    /**
     * (Item[], quantity, change str, Location[])
     * 
     * @author Daniel Boling
     */
    public function setupItem(?string $name, ?string $desc, ?int $quantity, ?string $change, ?Location $loc)
    {
        if ($this->getItem() == null)
        {
            $this->item = new Item();
        } else {
            $this->item = $this->getItem();
        }
        $this->setItemName($name);
        $this->setItemDescription($desc);
        $this->setQuantity($quantity);
        $this->setQuantityChange($change);
        $this->setLocation($loc);
    }

}
