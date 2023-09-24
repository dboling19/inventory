<?php

namespace App\Entity;

use App\Repository\PurchaseOrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
class PurchaseOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $po_num;

    #[ORM\ManyToOne(targetEntity:'vendor')]
    #[ORM\JoinColumn(name:'vendor_num', referencedColumnName:'num')]
    private ?string $vendor;

    #[ORM\Column(type:'string', length:3, nullable:false)]
    private ?string $terms;

    #[ORM\Column(type:'string', length:6, nullable:false)]
    private ?string $ship_code;

    #[ORM\Column(type: 'string', length:1, nullable:false)]
    private ?string $status;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:false)]
    private ?string $freight;

    #[ORM\Column(type: types::SMALLINT, length:1, nullable:false)]
    private ?int $received;

    #[ORM\Column(type: types::SMALLINT, length:1, nullable:false)]
    private ?int $paid;

    #[ORM\Column(type: 'datetime')]
    private ?\Datetime $order_date;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price;

    #[ORM\ManyToOne(targetEntity: 'item')]
    #[ORM\JoinColumn(nullable: false, name:'item', referencedColumnName:'name')]
    private ?Item $item;


    public function getPoNum(): ?string
    {
        return $this->po_num;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): static
    {
        $this->terms = $terms;

        return $this;
    }

    public function getShipCode(): ?string
    {
        return $this->ship_code;
    }

    public function setShipCode(string $ship_code): static
    {
        $this->ship_code = $ship_code;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFreight(): ?string
    {
        return $this->freight;
    }

    public function setFreight(string $freight): static
    {
        $this->freight = $freight;

        return $this;
    }

    public function getReceived(): ?int
    {
        return $this->received;
    }

    public function setReceived(int $received): static
    {
        $this->received = $received;

        return $this;
    }

    public function getPaid(): ?int
    {
        return $this->paid;
    }

    public function setPaid(int $paid): static
    {
        $this->paid = $paid;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getVendor(): ?vendor
    {
        return $this->vendor;
    }

    public function setVendor(?vendor $vendor): static
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getItem(): ?purchaseOrder
    {
        return $this->item;
    }

    public function setItem(?purchaseOrder $item): static
    {
        $this->item = $item;

        return $this;
    }
}
