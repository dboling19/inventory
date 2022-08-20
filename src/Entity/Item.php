<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="item", cascade={"persist", "remove"})
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=ItemLocation::class, mappedBy="item", cascade={"persist", "remove"})
     */
    private $itemlocation;

    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->itemlocation = new ArrayCollection();

        $this->date = new \DateTime('now');

        $this->item_loc = new ItemLocation();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // public function getLocation(): ?Location
    // {
    //     return $this->getItemLocation()->getLocation();
    // }

    public function addLocation(?Location $location): self
    {
        $this->item_loc->setLocation($location);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->getQuantity();
    }

    public function setQuantity(?int $quantity): self
    {
        $this->item_loc->setQuantity($quantity);

        return $this;
    }

    public function setQuantityChange(?string $change): self
    {
        $this->trans = new Transaction();
        $this->addTransaction($this->trans);
        $this->trans->setDate(new \DateTime());
        $this->trans->setQuantityChange($change);

        return $this;
    }

    /**
     * @return Collection<int, transaction>
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setItem($this);
        }

        return $this;
    }

    public function removeTransaction(transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getItem() === $this) {
                $transaction->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, itemlocation>
     */
    public function getItemlocation(): Collection
    {
        return $this->itemlocation;
    }

    public function addItemlocation(itemlocation $itemlocation): self
    {
        if (!$this->itemlocation->contains($itemlocation)) {
            $this->itemlocation[] = $itemlocation;
            $itemlocation->setItem($this);
        }

        return $this;
    }

    public function removeItemlocation(itemlocation $itemlocation): self
    {
        if ($this->itemlocation->removeElement($itemlocation)) {
            // set the owning side to null (unless already changed)
            if ($itemlocation->getItem() === $this) {
                $itemlocation->setItem(null);
            }
        }

        return $this;
    }

}
