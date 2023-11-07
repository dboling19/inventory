<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026233544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (item_code VARCHAR(8) NOT NULL, unit_code VARCHAR(10) NOT NULL, item_desc VARCHAR(50) NOT NULL, item_notes LONGTEXT DEFAULT NULL, item_exp_date DATETIME DEFAULT NULL, INDEX IDX_1F1B251EFBD3D1C2 (unit_code), PRIMARY KEY(item_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_location (item_code VARCHAR(8) NOT NULL, loc_code VARCHAR(8) NOT NULL, whs_code VARCHAR(8) NOT NULL, item_qty INT NOT NULL, INDEX IDX_32934100BF257463 (item_code), INDEX IDX_32934100FDEA9CFB (loc_code), INDEX IDX_329341001A4A8D93 (whs_code), PRIMARY KEY(item_code, loc_code, whs_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (loc_code VARCHAR(8) NOT NULL, loc_desc VARCHAR(255) NOT NULL, loc_notes LONGTEXT DEFAULT NULL, PRIMARY KEY(loc_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order (po_num INT AUTO_INCREMENT NOT NULL, vendor_code VARCHAR(10) DEFAULT NULL, terms_code VARCHAR(3) NOT NULL, po_ship_code VARCHAR(6) NOT NULL, po_status VARCHAR(1) NOT NULL, po_freight NUMERIC(9, 2) NOT NULL, po_received SMALLINT NOT NULL, po_paid SMALLINT NOT NULL, po_order_date DATETIME NOT NULL, po_total_cost NUMERIC(10, 2) NOT NULL, INDEX IDX_21E210B25DD83547 (vendor_code), INDEX IDX_21E210B26F6CFA46 (terms_code), PRIMARY KEY(po_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order_line (po_line SMALLINT NOT NULL, po_num INT NOT NULL, item_code VARCHAR(8) DEFAULT NULL, po_status VARCHAR(1) NOT NULL, qty_ordered NUMERIC(9, 2) DEFAULT NULL, qty_received NUMERIC(9, 2) DEFAULT NULL, qty_rejected NUMERIC(9, 2) DEFAULT NULL, qty_vouchered NUMERIC(9, 2) DEFAULT NULL, item_cost NUMERIC(9, 2) DEFAULT NULL, po_due_date DATETIME NOT NULL, po_received_date DATETIME DEFAULT NULL, item_unit VARCHAR(3) DEFAULT NULL, po_received SMALLINT NOT NULL, po_paid SMALLINT NOT NULL, item_quantity INT NOT NULL, INDEX IDX_90D6D92BBF257463 (item_code), PRIMARY KEY(po_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms (terms_code VARCHAR(3) NOT NULL, terms_desc VARCHAR(40) DEFAULT NULL, terms_due_days INT DEFAULT NULL, terms_disc_days INT DEFAULT NULL, terms_disc_pct NUMERIC(6, 3) DEFAULT NULL, terms_prox_day SMALLINT DEFAULT NULL, terms_prox_code SMALLINT DEFAULT NULL, terms_tax_disc NUMERIC(6, 3) DEFAULT NULL, terms_cash_only SMALLINT NOT NULL, terms_note_exists_flag SMALLINT NOT NULL, PRIMARY KEY(terms_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `transaction` (trans_num INT AUTO_INCREMENT NOT NULL, trans_qty_change VARCHAR(255) NOT NULL, trans_datetime DATETIME NOT NULL, name VARCHAR(8) NOT NULL, INDEX IDX_723705D15E237E06 (name), PRIMARY KEY(trans_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (unit_code VARCHAR(10) NOT NULL, unit_desc VARCHAR(50) NOT NULL, unit_notes LONGTEXT DEFAULT NULL, unit_precision INT NOT NULL, PRIMARY KEY(unit_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (vendor_code VARCHAR(10) NOT NULL, vendor_desc VARCHAR(50) NOT NULL, vendor_notes LONGTEXT DEFAULT NULL, vendor_addr VARCHAR(50) DEFAULT NULL, vendor_email VARCHAR(20) DEFAULT NULL, vendor_phone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(vendor_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse (whs_code VARCHAR(8) NOT NULL, whs_desc VARCHAR(255) NOT NULL, whs_addr VARCHAR(255) DEFAULT NULL, whs_notes LONGTEXT DEFAULT NULL, PRIMARY KEY(whs_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EFBD3D1C2 FOREIGN KEY (unit_code) REFERENCES unit (unit_code)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_32934100BF257463 FOREIGN KEY (item_code) REFERENCES item (item_code)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_32934100FDEA9CFB FOREIGN KEY (loc_code) REFERENCES location (loc_code)');
        $this->addSql('ALTER TABLE item_location ADD CONSTRAINT FK_329341001A4A8D93 FOREIGN KEY (whs_code) REFERENCES warehouse (whs_code)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B25DD83547 FOREIGN KEY (vendor_code) REFERENCES vendor (vendor_code)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B26F6CFA46 FOREIGN KEY (terms_code) REFERENCES terms (terms_code)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92BBF257463 FOREIGN KEY (item_code) REFERENCES item (item_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EFBD3D1C2');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_32934100BF257463');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_32934100FDEA9CFB');
        $this->addSql('ALTER TABLE item_location DROP FOREIGN KEY FK_329341001A4A8D93');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B25DD83547');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B26F6CFA46');
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92BBF257463');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_location');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE purchase_order_line');
        $this->addSql('DROP TABLE terms');
        $this->addSql('DROP TABLE `transaction`');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE vendor');
        $this->addSql('DROP TABLE warehouse');
    }
}
