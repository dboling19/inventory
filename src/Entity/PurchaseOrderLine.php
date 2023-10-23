<?php

namespace App\Entity;

use App\Repository\PurchaseOrderLineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderLineRepository::class)]
class PurchaseOrderLine
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', nullable:false)]
    private ?int $po_num;

    #[ORM\Column(type: types::SMALLINT, nullable:false)]
    private ?int $po_line;

    #[ORM\Column(type: 'string', length:1, nullable:false)]
    private ?int $po_status;

    #[ORM\ManyToOne(targetEntity:Item::class)]
    #[ORM\JoinColumn(name:'item', referencedColumnName:'item_name')]
    private ?Item $item;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_ordered = null;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_received = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_rejected = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_vouchered = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $item_cost = null;
    
    #[ORM\Column(type: 'datetime', nullable:false)]
    private ?\datetime $po_due_date;

    #[ORM\Column(type: 'datetime', nullable:true)]
    private ?\datetime $po_received_date = null;

    #[ORM\Column(type: 'string', length:3, nullable:true)]
    private ?string $item_unit = null;

    #[ORM\Column(type: types::SMALLINT, length:3, nullable:false)]
    private ?int $po_received;

    #[ORM\Column(type: types::SMALLINT, length:3, nullable:false)]
    private ?int $po_paid;

    #[ORM\Column(type: 'integer', nullable:false)]
    private ?int $item_quantity;

    public function getPoNum(): ?int
    {
        return $this->po_num;
    }

    public function getPoLine(): ?int
    {
        return $this->po_line;
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

    public function getQtyOrdered(): ?string
    {
        return $this->qty_ordered;
    }

    public function setQtyOrdered(?string $qty_ordered): static
    {
        $this->qty_ordered = $qty_ordered;

        return $this;
    }

    public function getQtyReceived(): ?string
    {
        return $this->qty_received;
    }

    public function setQtyReceived(?string $qty_received): static
    {
        $this->qty_received = $qty_received;

        return $this;
    }

    public function getQtyRejected(): ?string
    {
        return $this->qty_rejected;
    }

    public function setQtyRejected(?string $qty_rejected): static
    {
        $this->qty_rejected = $qty_rejected;

        return $this;
    }

    public function getQtyVouchered(): ?string
    {
        return $this->qty_vouchered;
    }

    public function setQtyVouchered(?string $qty_vouchered): static
    {
        $this->qty_vouchered = $qty_vouchered;

        return $this;
    }

    public function getItemCost(): ?string
    {
        return $this->item_cost;
    }

    public function setItemCost(?string $item_cost): static
    {
        $this->item_cost = $item_cost;

        return $this;
    }

    public function getPoDueDate(): ?\DateTimeInterface
    {
        return $this->po_due_date;
    }

    public function setPoDueDate(\DateTimeInterface $po_due_date): static
    {
        $this->po_due_date = $po_due_date;

        return $this;
    }

    public function getPoReceivedDate(): ?\DateTimeInterface
    {
        return $this->po_received_date;
    }

    public function setPoReceivedDate(?\DateTimeInterface $po_received_date): static
    {
        $this->po_received_date = $po_received_date;

        return $this;
    }

    public function getItemUnit(): ?string
    {
        return $this->item_unit;
    }

    public function setItemUnit(?string $item_unit): static
    {
        $this->unit = $item_unit;

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

    public function getItemQuantity(): ?int
    {
        return $this->item_quantity;
    }

    public function setItemQuantity(int $item_quantity): static
    {
        $this->item_quantity = $item_quantity;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

}
