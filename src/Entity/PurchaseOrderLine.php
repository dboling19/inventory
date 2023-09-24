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
    private ?int $status;

    #[ORM\ManyToOne(targetEntity:'item')]
    #[ORM\JoinColumn(name:'item', referencedColumnName:'name')]
    private ?string $item = null;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_ordered = null;

    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_received = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_rejected = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $qty_vouchered = null;
    
    #[ORM\Column(type: types::DECIMAL, precision:9, scale:2, nullable:true)]
    private ?string $itemcost = null;
    
    #[ORM\Column(type: 'datetime', nullable:false)]
    private ?\datetime $due_date;

    #[ORM\Column(type: 'datetime', nullable:true)]
    private ?\datetime $received_date = null;

    #[ORM\Column(type: 'string', length:3, nullable:true)]
    private ?string $unit = null;

    #[ORM\Column(type: types::SMALLINT, length:3, nullable:false)]
    private ?int $received;

    #[ORM\Column(type: types::SMALLINT, length:3, nullable:false)]
    private ?int $paid;

    #[ORM\Column(type: 'integer', nullable:false)]
    private ?int $quantity;

    public function getPoNum(): ?int
    {
        return $this->po_num;
    }

    public function getPoLine(): ?int
    {
        return $this->po_line;
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

    public function getItemcost(): ?string
    {
        return $this->itemcost;
    }

    public function setItemcost(?string $itemcost): static
    {
        $this->itemcost = $itemcost;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getReceivedDate(): ?\DateTimeInterface
    {
        return $this->received_date;
    }

    public function setReceivedDate(?\DateTimeInterface $received_date): static
    {
        $this->received_date = $received_date;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getItem(): ?item
    {
        return $this->item;
    }

    public function setItem(?item $item): static
    {
        $this->item = $item;

        return $this;
    }

}
