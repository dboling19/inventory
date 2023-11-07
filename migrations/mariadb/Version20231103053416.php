<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103053416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON purchase_order_line');
        $this->addSql('ALTER TABLE purchase_order_line CHANGE po_num po_num INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase_order_line ADD PRIMARY KEY (po_line)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON purchase_order_line');
        $this->addSql('ALTER TABLE purchase_order_line CHANGE po_num po_num INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_order_line ADD PRIMARY KEY (po_num, po_line)');
    }
}
