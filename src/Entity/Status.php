<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\Column(nullable:false)]
    private ?string $status_code = null;

    #[ORM\Column(length: 255, nullable:false)]
    private ?string $status_desc = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $status_notes = null;

    public function getStatusCode(): ?string
    {
        return $this->status_code;
    }

    public function setStatusCode(string $status_code): static
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getStatusDesc(): ?string
    {
        return $this->status_desc;
    }

    public function setStatusDesc(string $status_desc): static
    {
        $this->status_desc = $status_desc;

        return $this;
    }

    public function getStatusNotes(): ?string
    {
        return $this->status_notes;
    }

    public function setStatusNotes(?string $status_notes): static
    {
        $this->status_notes = $status_notes;

        return $this;
    }
}
