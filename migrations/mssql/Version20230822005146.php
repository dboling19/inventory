<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822005146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_order (id INT IDENTITY NOT NULL, item_id INT NOT NULL, quantity INT NOT NULL, purchase_date DATE NOT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_21E210B2126F525E ON purchase_order (item_id)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:date_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'purchase_order\', N\'COLUMN\', purchase_date');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B2126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE purchase_order DROP CONSTRAINT FK_21E210B2126F525E');
        $this->addSql('DROP TABLE purchase_order');
    }
}
