<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type:'string', length:255, nullable:false)]
    private ?string $name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description;

    #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:"item", cascade:["persist", "remove"])]
    private $transaction;

    #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:"item", cascade:["persist", "remove"])]
    private $itemlocation;

    #[ORM\Column(type:'datetime', nullable:true)]
    private $exp_date;

    private $quantity;


    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->itemlocation = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function setDescription(string $description): self
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

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction->add($transaction);
            $transaction->setItem($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
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

    public function addItemlocation(ItemLocation $itemlocation): self
    {
        if (!$this->itemlocation->contains($itemlocation)) {
            $this->itemlocation->add($itemlocation);
            $itemlocation->setItem($this);
        }

        return $this;
    }

    public function removeItemlocation(ItemLocation $itemlocation): self
    {
        if ($this->itemlocation->removeElement($itemlocation)) {
            // set the owning side to null (unless already changed)
            if ($itemlocation->getItem() === $this) {
                $itemlocation->setItem(null);
            }
        }

        return $this;
    }

    public function getExpDate()
    {
        return $this->exp_date;
    }

    public function setExpDate( $exp_date): self
    {
        $this->exp_date = $exp_date;

        return $this;
    }

}
