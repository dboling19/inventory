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
    private ?string $terms_desc = null;

    #[ORM\Column(nullable: true)]
    private ?int $terms_due_days = null;

    #[ORM\Column(nullable:true)]
    private ?int $terms_disc_days = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3, nullable:true)]
    private ?string $terms_disc_pct = null;

    #[ORM\Column(type: Types::SMALLINT, nullable:true)]
    private ?int $terms_prox_day = null;

    #[ORM\Column(type: Types::SMALLINT, length:2, nullable:true)]
    private ?int $terms_prox_code = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3, nullable:true)]
    private ?string $terms_tax_disc = null;

    #[ORM\Column(type: Types::SMALLINT, length:1, nullable:false)]
    private ?int $terms_cash_only = 0;

    #[ORM\Column(type: Types::SMALLINT, length:1, nullable:false)]
    private ?int $terms_note_exists_flag = 0;

    public function getTermsCode(): ?string
    {
        return $this->terms_code;
    }

    public function setTermsCode(string $terms_code): static
    {
        $this->terms_code = $terms_code;

        return $this;
    }

    public function getTermsDesc(): ?string
    {
        return $this->terms_desc;
    }

    public function setTermsDesc(?string $terms_desc): static
    {
        $this->terms_desc = $terms_desc;

        return $this;
    }

    public function getTermsDueDays(): ?int
    {
        return $this->terms_due_days;
    }

    public function setTermsDueDays(?int $terms_due_days): static
    {
        $this->terms_due_days = $terms_due_days;

        return $this;
    }

    public function getTermsDiscDays(): ?int
    {
        return $this->terms_disc_days;
    }

    public function setTermsDiscDays(?int $terms_disc_days): static
    {
        $this->terms_disc_days = $terms_disc_days;

        return $this;
    }

    public function getTermsDiscPct(): ?string
    {
        return $this->terms_disc_pct;
    }

    public function setTermsDiscPct(?string $terms_disc_pct): static
    {
        $this->terms_disc_pct = $terms_disc_pct;

        return $this;
    }

    public function getTermsProxDay(): ?int
    {
        return $this->terms_prox_day;
    }

    public function setTermsProxDay(?int $terms_prox_day): static
    {
        $this->terms_prox_day = $terms_prox_day;

        return $this;
    }

    public function getTermsTaxDisc(): ?string
    {
        return $this->terms_tax_disc;
    }

    public function setTermsTaxDisc(?string $terms_tax_disc): static
    {
        $this->terms_tax_disc = $terms_tax_disc;

        return $this;
    }

    public function getTermsCashOnly(): ?int
    {
        return $this->terms_cash_only;
    }

    public function setTermsCashOnly(int $terms_cash_only): static
    {
        $this->terms_cash_only = $terms_cash_only;

        return $this;
    }

    public function getTermsNoteExistsFlag(): ?int
    {
        return $this->terms_note_exists_flag;
    }

    public function setTermsNoteExistsFlag(int $terms_note_exists_flag): static
    {
        $this->terms_note_exists_flag = $terms_note_exists_flag;

        return $this;
    }

    public function getTermsProxCode(): ?int
    {
        return $this->terms_prox_code;
    }

    public function setTermsProxCode(?int $terms_prox_code): static
    {
        $this->terms_prox_code = $terms_prox_code;

        return $this;
    }
}
