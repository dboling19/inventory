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
    private ?string $vendor_code;

    #[ORM\Column(type: 'string', length:50, nullable:false)]
    private ?string $vendor_desc;

    #[ORM\Column(type: types::TEXT, nullable:true)]
    private ?string $vendor_notes;

    #[ORM\Column(type: 'string', length:50, nullable:true)]
    private ?string $vendor_addr = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $vendor_email = null;

    #[ORM\Column(type: 'string', length:20, nullable:true)]
    private ?string $vendor_phone = null;

    #[ORM\OneToMany(targetEntity:PurchaseOrder::class, mappedBy:'vendor')]
    #[ORM\JoinColumn(name:'po_num', referencedColumnName:'po_num', nullable:true)]
    private Collection $vendor_pos;

    public function __construct()
    {
        $this->vendor_pos = new ArrayCollection();
    }

    public function getVendorCode(): ?string
    {
        return $this->vendor_code;
    }

    public function setVendorCode(?string $vendor_code): static
    {
        $this->vendor_code = strtoupper($vendor_code);

        return $this;
    }

    public function setVendorDesc(?string $vendor_desc): static
    {
        $this->vendor_desc = $vendor_desc;

        return $this;
    }

    public function getVendorDesc(): ?string
    {
        return $this->vendor_desc;
    }

    public function getVendorNotes(): ?string
    {
        return $this->vendor_notes;
    }

    public function setVendorNotes(?string $vendor_notes): static
    {
        $this->vendor_notes = $vendor_notes;

        return $this;
    }

    public function getVendorAddr(): ?string
    {
        return $this->vendor_addr;
    }

    public function setVendorAddr(?string $vendor_addr): static
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
    public function getVendorPOs(): Collection
    {
        return $this->vendor_pos;
    }

    public function addVendorPO(purchaseOrder $vendor_po): static
    {
        if (!$this->vendor_pos->contains($vendor_po)) {
            $this->vendor_pos->add($vendor_po);
            $vendor_po->setVendor($this);
        }

        return $this;
    }

    public function removeVendorPO(purchaseOrder $vendor_po): static
    {
        if ($this->vendor_pos->removeElement($vendor_po)) {
            // set the owning side to null (unless already changed)
            if ($vendor_po->getVendor() === $this) {
                $vendor_po->setVendor(null);
            }
        }

        return $this;
    }
}
