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
    private ?DateTimeInterface $exp_date;

    private $date;
    private $item_loc;
    private $trans;

    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->itemlocation = new ArrayCollection();
        $this->date = new \DateTime('now');
        $this->item_loc = new ItemLocation();
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

    public function getLocation(): ?Location
    {
        return $this->getItemLocation()->getLocation();
    }

    public function addLocation(?Location $location): self
    {
        $this->item_loc->setLocation($location);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->getQuantity();
    }

    public function setQuantity(?int $quantity): self
    {
        $this->item_loc->setQuantity($quantity);

        return $this;
    }

    public function setQuantityChange(?string $change): self
    {
        $this->trans = new Transaction();
        $this->addTransaction($this->trans);
        $this->trans->setDate(new \DateTime());
        $this->trans->setQuantityChange($change);

        return $this;
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

    public function getExpDate(): ?\DateTimeInterface
    {
        return $this->exp_date;
    }

    public function setExpDate(\DateTimeInterface $exp_date): self
    {
        $this->exp_date = $exp_date;

        return $this;
    }

}
