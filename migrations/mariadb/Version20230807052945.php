<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807052945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, exp_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_location (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, location_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_32934100126F525E (item_id), INDEX IDX_3293410064D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, quantity_change VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_723705D1126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_32934100126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_3293410064D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_32934100126F525E');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_3293410064D218E');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1126F525E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_location');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE transaction');
    }
}
