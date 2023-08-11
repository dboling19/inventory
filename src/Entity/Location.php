<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:LocationRepository::class)]
class Location
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id;

  #[ORM\Column(type:'string', length:255, nullable:false)]
  private ?string $name;

  #[ORM\OneToMany(targetEntity:ItemLocation::class, mappedBy:'location', orphanRemoval:true, cascade:['persist'])]
  private $itemlocation;

  #[ORM\OneToMany(targetEntity:Transaction::class, mappedBy:"location", cascade:["persist", "remove"])]
  private $transaction;


  public function __construct()
  {
      $this->itemlocation = new ArrayCollection();
      $this->transaction = new ArrayCollection();
  }

  public function getId(): ?int
  {
      return $this->id;
  }

  public function getName(): ?string
  {
      return $this->name;
  }

  public function setName(string $name): static
  {
      $this->name = $name;

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
          $itemlocation->setLocation($this);
      }

      return $this;
  }

  public function removeItemlocation(ItemLocation $itemlocation): static
  {
      if ($this->itemlocation->removeElement($itemlocation)) {
          // set the owning side to null (unless already changed)
          if ($itemlocation->getLocation() === $this) {
              $itemlocation->setLocation(null);
          }
      }

      return $this;
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
          $transaction->setLocation($this);
      }

      return $this;
  }

  public function removeTransaction(Transaction $transaction): static
  {
      if ($this->transaction->removeElement($transaction)) {
          // set the owning side to null (unless already changed)
          if ($transaction->getLocation() === $this) {
              $transaction->setLocation(null);
          }
      }

      return $this;
  }

}
