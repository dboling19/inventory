<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119035500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_order CHANGE po_order_date po_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE status CHANGE status_code status_code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status CHANGE status_code status_code VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE purchase_order CHANGE po_date po_order_date DATETIME NOT NULL');
    }
}
