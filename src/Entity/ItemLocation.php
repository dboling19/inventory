<?php

namespace App\Entity;

use App\Repository\ItemLocationRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:ItemLocationRepository::class)]
class ItemLocation
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private $id;

  #[ORM\Column]
  private $quantity;

  #[ORM\ManyToOne(targetEntity:Item::class, inversedBy:"itemlocation", cascade:['persist'])]
  #[ORM\JoinColumn(nullable:true)]
  private $item;

  #[ORM\ManyToOne(targetEntity:Location::class, inversedBy:'itemlocation', cascade:['persist'])]
  #[ORM\JoinColumn(nullable:true)]
  private $location;

  private $trans;

  public function getId(): ?int
  {
      return $this->id;
  }

  public function getQuantity(): ?int
  {
      return $this->quantity;
  }

  public function setQuantity(int $quantity): self
  {
      $this->quantity = $quantity;

      return $this;
  }

  public function getItem(): ?Item
  {
      return $this->item;
  }

  public function setItem(?Item $item): self
  {
      $this->item = $item;

      return $this;
  }

  public function getLocation(): ?Location
  {
      return $this->location;
  }

  public function setLocation(?Location $location): self
  {
      $this->location = $location;

      return $this;
  }

  public function getItemName(): ?string
  {
      return $this->getItem()->getName();
  }

  public function setItemName(?string $name): self
  {
      $this->getItem()->setName($name);

      return $this;
  }

  public function getItemDescription(): ?string
  {
      return $this->getItem()->getDescription();
  }

  public function setItemDescription(?string $description): self
  {
      $this->getItem()->setDescription($description);

      return $this;
  }

  public function setQuantityChange(?string $change): self
  {
      $this->trans = new Transaction();
      $this->getItem()->addTransaction($this->trans);
      $this->trans->setDate(new \DateTime());
      $this->trans->setQuantityChange($change);

      return $this;
  }

}
