<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230811015540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT IDENTITY NOT NULL, name NVARCHAR(255) NOT NULL, description VARCHAR(MAX), exp_date DATETIME2(6), PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE item_location (id INT IDENTITY NOT NULL, item_id INT, location_id INT, quantity INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_32934100126F525E ON item_location (item_id)');
        $this->addSql('CREATE INDEX IDX_3293410064D218E ON item_location (location_id)');
        $this->addSql('CREATE TABLE location (id INT IDENTITY NOT NULL, name NVARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE [transaction] (id INT IDENTITY NOT NULL, item_id INT NOT NULL, location_id INT NOT NULL, quantity_change NVARCHAR(255) NOT NULL, datetime DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_723705D1126F525E ON [transaction] (item_id)');
        $this->addSql('CREATE INDEX IDX_723705D164D218E ON [transaction] (location_id)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_32934100126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_3293410064D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE [transaction] ADD CONSTRAINT FK_723705D1126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE [transaction] ADD CONSTRAINT FK_723705D164D218E FOREIGN KEY (location_id) REFERENCES location (id)');
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
        $this->addSql('ALTER TABLE item_location DROP CONSTRAINT FK_32934100126F525E');
        $this->addSql('ALTER TABLE item_location DROP CONSTRAINT FK_3293410064D218E');
        $this->addSql('ALTER TABLE [transaction] DROP CONSTRAINT FK_723705D1126F525E');
        $this->addSql('ALTER TABLE [transaction] DROP CONSTRAINT FK_723705D164D218E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_location');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE [transaction]');
    }
}
