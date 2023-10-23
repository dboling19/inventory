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
    private ?string $vendor_num;

    #[ORM\Column(type: 'string', length:50, nullable:false)]
    private ?string $vendor_name;

    #[ORM\Column(type: types::TEXT, nullable:true)]
    private ?string $vendor_desc;

    #[ORM\Column(type: 'string', length:50, nullable:true)]
    private ?string $vendor_addr = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $vendor_email = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $vendor_phone = null;

    #[ORM\OneToMany(targetEntity:PurchaseOrder::class, mappedBy:'vendor')]
    #[ORM\JoinColumn(name:'purchaseOrder', referencedColumnName:'po_num', nullable:true)]
    private Collection $purchaseOrders;

    public function __construct()
    {
        $this->purchaseOrders = new ArrayCollection();
    }

    public function getVendorNum(): ?string
    {
        return $this->vendor_num;
    }

    public function setVendorNum(?string $vendor_num): static
    {
        $this->vendor_num = $vendor_num;

        return $this;
    }

    public function setVendorName(?string $vendor_name): static
    {
        $this->vendor_name = $vendor_name;

        return $this;
    }

    public function getVendorName(): ?string
    {
        return $this->vendor_name;
    }

    public function getVendorDesc(): ?string
    {
        return $this->vendor_desc;
    }

    public function setVendorDesc(?string $vendor_desc): static
    {
        $this->vendor_desc = $vendor_desc;

        return $this;
    }

    public function getVendorAddress(): ?string
    {
        return $this->vendor_addr;
    }

    public function setVendorAddress(?string $vendor_addr): static
    {
        $this->vendor_addr = $vendor_addr;

        return $this;
    }

    public function getVendorEmail(): ?string
    {
        return $this->vendor_email;
    }

    public function setVendorEmail(?string $vendor_email): static
    {
        $this->vendor_email = $vendor_email;

        return $this;
    }

    public function getVendorPhone(): ?string
    {
        return $this->vendor_phone;
    }

    public function setVendorPhone(?string $vendor_phone): static
    {
        $this->vendor_phone = $vendor_phone;

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
