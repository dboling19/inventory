<?php

namespace App\Entity;

use App\Repository\TermsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermsRepository::class)]
class Terms
{
    #[ORM\Id]
    #[ORM\Column(length: 3, nullable:false)]
    private ?string $terms_code;

    #[ORM\Column(length: 40, nullable:true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $due_days = null;

    #[ORM\Column]
    private ?int $disc_days = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    private ?string $disc_pct = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $prox_day = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3, nullable: true)]
    private ?string $tax_disc = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $cash_only = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $note_exists_flag = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $record_date = null;

    #[ORM\Column(length: 30)]
    private ?string $created_by = null;

    #[ORM\Column(length: 30)]
    private ?string $updated_by = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    public function getTermsCode(): ?string
    {
        return $this->terms_code;
    }

    public function setTermsCode(string $terms_code): static
    {
        $this->terms_code = $terms_code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDays(): ?int
    {
        return $this->due_days;
    }

    public function setDueDays(?int $due_days): static
    {
        $this->due_days = $due_days;

        return $this;
    }

    public function getDiscDays(): ?int
    {
        return $this->disc_days;
    }

    public function setDiscDays(int $disc_days): static
    {
        $this->disc_days = $disc_days;

        return $this;
    }

    public function getDiscPct(): ?string
    {
        return $this->disc_pct;
    }

    public function setDiscPct(string $disc_pct): static
    {
        $this->disc_pct = $disc_pct;

        return $this;
    }

    public function getProxDay(): ?int
    {
        return $this->prox_day;
    }

    public function setProxDay(?int $prox_day): static
    {
        $this->prox_day = $prox_day;

        return $this;
    }

    public function getTaxDisc(): ?string
    {
        return $this->tax_disc;
    }

    public function setTaxDisc(?string $tax_disc): static
    {
        $this->tax_disc = $tax_disc;

        return $this;
    }

    public function getCashOnly(): ?int
    {
        return $this->cash_only;
    }

    public function setCashOnly(int $cash_only): static
    {
        $this->cash_only = $cash_only;

        return $this;
    }

    public function getNoteExistsFlag(): ?int
    {
        return $this->note_exists_flag;
    }

    public function setNoteExistsFlag(int $note_exists_flag): static
    {
        $this->note_exists_flag = $note_exists_flag;

        return $this;
    }

    public function getRecordDate(): ?\DateTimeInterface
    {
        return $this->record_date;
    }

    public function setRecordDate(\DateTimeInterface $record_date): static
    {
        $this->record_date = $record_date;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    public function setCreatedBy(string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(string $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): static
    {
        $this->create_date = $create_date;

        return $this;
    }
}
