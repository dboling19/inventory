<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;
use PhpParser\Node\Expr\Cast\Array_;

#[ORM\Entity(repositoryClass:ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\Column(type:'string', length:50, nullable:false)]
    private ?string $item_name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $item_desc = null;

    #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:"item", cascade:["persist", "remove"])]
    #[ORM\InverseJoinColumn(nullable:false, name:'item', referencedColumnName:'item')]
    #[ORM\InverseJoinColumn(nullable:false, name:'location', referencedColumnName:'location')]
    private $transaction;

    #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:"item", cascade:["persist", "remove"])]
    #[ORM\JoinColumn(name:'item', referencedColumnName:'item')]
    #[ORM\JoinColumn(name:'location', referencedColumnName:'location')]
    private $itemlocation;

    #[ORM\Column(type:'datetime', nullable:true)]
    private $item_exp_date = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: PurchaseOrder::class)]
    private Collection $purchaseOrders;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'unit', referencedColumnName: 'unit_code', nullable: false)]
    private ?Unit $item_unit;

    private $locations;


    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->itemlocation = new ArrayCollection();
        $this->purchaseOrders = new ArrayCollection();
    }

    public function getItemName(): ?string
    {
        return $this->item_name;
    }

    public function setItemName(string $item_name): self
    {
        $this->item_name = $item_name;

        return $this;
    }

    public function getItemDesc(): ?string
    {
        return $this->item_desc;
    }

    public function setItemDesc(?string $item_desc): static
    {
        $this->item_desc = $item_desc;

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
            $transaction->setItem($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
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
            $itemlocation->setItem($this);
        }

        return $this;
    }

    public function removeItemlocation(ItemLocation $itemlocation): static
    {
        if ($this->itemlocation->removeElement($itemlocation)) {
            // set the owning side to null (unless already changed)
            if ($itemlocation->getItem() === $this) {
                $itemlocation->setItem(null);
            }
        }

        return $this;
    }

    public function getItemExpDate(): ?\DateTimeInterface
    {
        return $this->item_exp_date;
    }

    public function setItemExpDate(?\DateTimeInterface $item_exp_date): static
    {
        $this->item_exp_date = $item_exp_date;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseOrder>
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    public function addPurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        if (!$this->purchaseOrders->contains($purchaseOrder)) {
            $this->purchaseOrders->add($purchaseOrder);
            $purchaseOrder->setItem($this);
        }

        return $this;
    }

    public function removePurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        if ($this->purchaseOrders->removeElement($purchaseOrder)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrder->getItem() === $this) {
                $purchaseOrder->setItem(null);
            }
        }

        return $this;
    }

    public function getItemUnit(): ?Unit
    {
        return $this->item_unit;
    }

    public function setItemUnit(?Unit $item_unit): static
    {
        $this->item_unit = $item_unit;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        $this->locations = new ArrayCollection();
        foreach ($this->itemlocation as $itemlocation) 
        {
            $this->locations->add($itemlocation->getLocation());
        }

        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->getLocations()->contains($location)) {
            $itemlocation = new ItemLocation;
            $itemlocation->setLocation($location);
            $itemlocation->setItem($this);
            $this->itemlocation->add($itemlocation);
        }

        return $this;
    }

}
