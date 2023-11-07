<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103052046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON purchase_order_line');
        $this->addSql('ALTER TABLE purchase_order_line CHANGE po_line po_line INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92B11EE2576 FOREIGN KEY (po_num) REFERENCES purchase_order (po_num)');
        $this->addSql('CREATE INDEX IDX_90D6D92B11EE2576 ON purchase_order_line (po_num)');
        $this->addSql('ALTER TABLE purchase_order_line ADD PRIMARY KEY (po_num, po_line)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92B11EE2576');
        $this->addSql('DROP INDEX IDX_90D6D92B11EE2576 ON purchase_order_line');
        $this->addSql('DROP INDEX `PRIMARY` ON purchase_order_line');
        $this->addSql('ALTER TABLE purchase_order_line CHANGE po_line po_line SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE purchase_order_line ADD PRIMARY KEY (po_num)');
    }
}
