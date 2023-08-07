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

  public function __construct()
  {
      $this->itemlocation = new ArrayCollection();
  }

  public function getId(): ?int
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

  /**
   * @return Collection<int, itemlocation>
   */
  public function getItemlocation(): Collection
  {
      return $this->itemlocation;
  }

  public function addItemlocation(itemlocation $itemlocation): self
  {
      if (!$this->itemlocation->contains($itemlocation)) {
          $this->itemlocation[] = $itemlocation;
          $itemlocation->setLocation($this);
      }

      return $this;
  }

  public function removeItemlocation(itemlocation $itemlocation): self
  {
      if ($this->itemlocation->removeElement($itemlocation)) {
          // set the owning side to null (unless already changed)
          if ($itemlocation->getLocation() === $this) {
              $itemlocation->setLocation(null);
          }
      }

      return $this;
  }

}
