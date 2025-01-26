<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250126191150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD is_deleted TINYINT(1) DEFAULT 0 NOT NULL, CHANGE warehouse_id warehouse_id INT DEFAULT NULL, CHANGE status status ENUM("in_stock", "out_of_stock"), CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP is_deleted, CHANGE warehouse_id warehouse_id INT NOT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'now()\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'now()\' NOT NULL');
    }
}
