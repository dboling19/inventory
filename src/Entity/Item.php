<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;


#[ORM\Entity(repositoryClass:ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\Column(type:'string', length:50, nullable:false)]
    private ?string $name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description;

    #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:"item", cascade:["persist", "remove"])]
    #[ORM\InverseJoinColumn(nullable:false, name:'item', referencedColumnName:'item')]
    #[ORM\InverseJoinColumn(nullable:false, name:'location', referencedColumnName:'location')]
    private $transaction;

    #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:"item", cascade:["persist", "remove"])]
    #[ORM\JoinColumn(name:'item', referencedColumnName:'item')]
    #[ORM\JoinColumn(name:'location', referencedColumnName:'location')]
    private $itemlocation;

    #[ORM\Column(type:'datetime', nullable:true)]
    private $exp_date;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: PurchaseOrder::class)]
    private Collection $purchaseOrders;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'unit', referencedColumnName: 'code', nullable: false)]
    private ?Unit $unit;


    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->itemlocation = new ArrayCollection();
        $this->purchaseOrders = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        $quantity = 0;
        foreach ($this->itemlocation as $itemlocation)
        {
            $quantity += $itemlocation->getQuantity();
        }
        return $quantity;
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

    public function getExpDate(): ?\DateTimeInterface
    {
        return $this->exp_date;
    }

    public function setExpDate(?\DateTimeInterface $exp_date): static
    {
        $this->exp_date = $exp_date;

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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

}
