<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231023015648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (item_name VARCHAR(50) NOT NULL, unit VARCHAR(10) NOT NULL, item_desc LONGTEXT DEFAULT NULL, item_exp_date DATETIME DEFAULT NULL, INDEX IDX_1F1B251EDCBB0C53 (unit), PRIMARY KEY(item_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_location (item VARCHAR(50) NOT NULL, location VARCHAR(255) NOT NULL, quantity INT NOT NULL, INDEX IDX_329341001F1B251E (item), INDEX IDX_329341005E9E89CB (location), PRIMARY KEY(item, location)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (loc_name VARCHAR(255) NOT NULL, PRIMARY KEY(loc_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order (po_num INT AUTO_INCREMENT NOT NULL, vendor_num VARCHAR(10) DEFAULT NULL, terms VARCHAR(3) NOT NULL, po_ship_code VARCHAR(6) NOT NULL, po_status VARCHAR(1) NOT NULL, po_freight NUMERIC(9, 2) NOT NULL, po_received SMALLINT NOT NULL, po_paid SMALLINT NOT NULL, po_order_date DATETIME NOT NULL, po_price NUMERIC(10, 2) NOT NULL, INDEX IDX_21E210B27E6DE495 (vendor_num), INDEX IDX_21E210B288A23F71 (terms), PRIMARY KEY(po_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order_line (po_num INT NOT NULL, item VARCHAR(50) DEFAULT NULL, po_line SMALLINT NOT NULL, po_status VARCHAR(1) NOT NULL, qty_ordered NUMERIC(9, 2) DEFAULT NULL, qty_received NUMERIC(9, 2) DEFAULT NULL, qty_rejected NUMERIC(9, 2) DEFAULT NULL, qty_vouchered NUMERIC(9, 2) DEFAULT NULL, item_cost NUMERIC(9, 2) DEFAULT NULL, po_due_date DATETIME NOT NULL, po_received_date DATETIME DEFAULT NULL, item_unit VARCHAR(3) DEFAULT NULL, po_received SMALLINT NOT NULL, po_paid SMALLINT NOT NULL, item_quantity INT NOT NULL, INDEX IDX_90D6D92B1F1B251E (item), PRIMARY KEY(po_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms (terms_code VARCHAR(3) NOT NULL, terms_desc VARCHAR(40) DEFAULT NULL, terms_due_days INT DEFAULT NULL, terms_disc_days INT DEFAULT NULL, terms_disc_pct NUMERIC(6, 3) DEFAULT NULL, terms_prox_day SMALLINT DEFAULT NULL, terms_prox_code SMALLINT DEFAULT NULL, terms_tax_disc NUMERIC(6, 3) DEFAULT NULL, terms_cash_only SMALLINT NOT NULL, terms_note_exists_flag SMALLINT NOT NULL, PRIMARY KEY(terms_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `transaction` (trans_num INT AUTO_INCREMENT NOT NULL, trans_quantity_change VARCHAR(255) NOT NULL, trans_datetime DATETIME NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_723705D15E237E06 (name), PRIMARY KEY(trans_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (unit_code VARCHAR(10) NOT NULL, unit_name VARCHAR(50) NOT NULL, unit_desc LONGTEXT DEFAULT NULL, unit_precision INT NOT NULL, PRIMARY KEY(unit_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (vendor_num VARCHAR(10) NOT NULL, vendor_name VARCHAR(50) NOT NULL, vendor_desc LONGTEXT DEFAULT NULL, vendor_addr VARCHAR(50) DEFAULT NULL, vendor_email VARCHAR(20) DEFAULT NULL, vendor_phone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(vendor_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EDCBB0C53 FOREIGN KEY (unit) REFERENCES unit (unit_code)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_329341001F1B251E FOREIGN KEY (item) REFERENCES item (item_name)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_329341005E9E89CB FOREIGN KEY (location) REFERENCES location (loc_name)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B27E6DE495 FOREIGN KEY (vendor_num) REFERENCES vendor (vendor_num)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B288A23F71 FOREIGN KEY (terms) REFERENCES terms (terms_code)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92B1F1B251E FOREIGN KEY (item) REFERENCES item (item_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EDCBB0C53');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_329341001F1B251E');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_329341005E9E89CB');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B27E6DE495');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B288A23F71');
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92B1F1B251E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_location');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE purchase_order_line');
        $this->addSql('DROP TABLE terms');
        $this->addSql('DROP TABLE `transaction`');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE vendor');
    }
}
