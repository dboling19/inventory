<?php

namespace App\Entity;

use App\Repository\VendorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VendorRepository::class)]
class Vendor
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length:10, nullable:false)]
    private ?int $num;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length:50, nullable:false)]
    private ?int $name;

    #[ORM\Column(type: types::TEXT, nullable:true)]
    private ?string $desc;

    #[ORM\Column(type: 'string', length:50, nullable:true)]
    private ?string $address = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $phone = null;

    #[ORM\OneToMany(targetEntity:'purchaseOrder', mappedBy:'vendor')]
    #[ORM\JoinColumn(name:'purchaseOrder', referencedColumnName:'po_num', nullable:true)]
    private Collection $purchaseOrders;

    public function __construct()
    {
        $this->purchaseOrders = new ArrayCollection();
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
    }

    public function setDesc(?string $desc): static
    {
        $this->desc = $desc;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, purchaseOrder>
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    public function addPurchaseOrder(purchaseOrder $purchaseOrder): static
    {
        if (!$this->purchaseOrders->contains($purchaseOrder)) {
            $this->purchaseOrders->add($purchaseOrder);
            $purchaseOrder->setVendor($this);
        }

        return $this;
    }

    public function removePurchaseOrder(purchaseOrder $purchaseOrder): static
    {
        if ($this->purchaseOrders->removeElement($purchaseOrder)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrder->getVendor() === $this) {
                $purchaseOrder->setVendor(null);
            }
        }

        return $this;
    }
}
