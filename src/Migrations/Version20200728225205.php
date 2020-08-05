<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200728225205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE historial CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE proveedor_id proveedor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto ADD control_stock TINYINT(1) NOT NULL, CHANGE marca marca VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedor CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renglon_historial CHANGE detalle detalle VARCHAR(255) DEFAULT NULL, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE historial CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE proveedor_id proveedor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto DROP control_stock, CHANGE marca marca VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedor CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renglon_historial CHANGE detalle detalle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
