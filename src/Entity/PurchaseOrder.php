<?php

namespace App\Entity;

use App\Repository\PurchaseOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
class PurchaseOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $po_num;

    #[ORM\ManyToOne(targetEntity:Vendor::class)]
    #[ORM\JoinColumn(name:'vendor_code', referencedColumnName:'vendor_code')]
    private $vendor;

    #[ORM\ManyToOne(targetEntity:Terms::class)]
    #[ORM\JoinColumn(nullable:false, name:'terms_code', referencedColumnName:'terms_code')]
    private ?Terms $terms;

    #[ORM\Column(type:'string', length:6, nullable:false)]
    private ?string $po_ship_code;

    #[ORM\Column(type: 'string', length:1, nullable:false)]
    private ?string $po_status;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:false)]
    private ?string $po_freight = '0';

    #[ORM\Column(type: types::SMALLINT, length:1, nullable:false)]
    private ?int $po_received = 0;

    #[ORM\Column(type: types::SMALLINT, length:1, nullable:false)]
    private ?int $po_paid = 0;

    #[ORM\Column(type: 'datetime')]
    private ?\Datetime $po_order_date;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $po_total_cost = '0';

    #[ORM\OneToMany(targetEntity: PurchaseOrderLine::class, mappedBy:'po')]
    private $po_lines;


    public function __construct()
    {
        $this->po_lines = new ArrayCollection();
    }

    public function getPoNum(): ?int
    {
        return $this->po_num;
    }

    public function getPoTotalCost(): ?string
    {
        return $this->po_total_cost;
    }

    public function setPoTotalCost(string $po_total_cost): static
    {
        $this->po_total_cost = $po_total_cost;

        return $this;
    }

    public function getPoTerms(): ?Terms
    {
        return $this->terms;
    }

    public function setPoTerms(?Terms $terms): static
    {
        $this->terms = $terms;

        return $this;
    }

    public function getPoShipCode(): ?string
    {
        return $this->po_ship_code;
    }

    public function setPoShipCode(string $po_ship_code): static
    {
        $this->po_ship_code = $po_ship_code;

        return $this;
    }

    public function getPoStatus(): ?string
    {
        return $this->po_status;
    }

    public function setPoStatus(string $po_status): static
    {
        $this->po_status = $po_status;

        return $this;
    }

    public function getPoFreight(): ?string
    {
        return $this->po_freight;
    }

    public function setPoFreight(string $po_freight): static
    {
        $this->po_freight = $po_freight;

        return $this;
    }

    public function getPoReceived(): ?int
    {
        return $this->po_received;
    }

    public function setPoReceived(int $po_received): static
    {
        $this->po_received = $po_received;

        return $this;
    }

    public function getPoPaid(): ?int
    {
        return $this->po_paid;
    }

    public function setPoPaid(int $po_paid): static
    {
        $this->po_paid = $po_paid;

        return $this;
    }

    public function getPoOrderDate(): ?\DateTimeInterface
    {
        return $this->po_order_date;
    }

    public function setPoOrderDate(\DateTimeInterface $po_order_date): static
    {
        $this->po_order_date = $po_order_date;

        return $this;
    }

    public function getPoVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setPoVendor(?Vendor $vendor): static
    {
        $this->vendor = $vendor;

        return $this;
    }
    
    /**
     * @return Collection<int, PurchaseOrderLine>
     */
    public function getPoLines(): Collection
    {
        return $this->po_lines;
    }

    public function addPoLine(PurchaseOrderLine $poLine): static
    {
        if (!$this->po_lines->contains($poLine)) {
            $this->po_lines->add($poLine);
            $poLine->setPo($this);
        }

        return $this;
    }

    public function removePoLine(PurchaseOrderLine $poLine): static
    {
        if ($this->po_lines->removeElement($poLine)) {
            // set the owning side to null (unless already changed)
            if ($poLine->getPo() === $this) {
                $poLine->setPo(null);
            }
        }

        return $this;
    }
}
