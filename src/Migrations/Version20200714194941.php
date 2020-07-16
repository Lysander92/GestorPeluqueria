<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714194941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE proveedor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, direccion VARCHAR(255) DEFAULT NULL, telefono INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cliente CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE historial ADD proveedor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE historial ADD CONSTRAINT FK_26950652CB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id)');
        $this->addSql('CREATE INDEX IDX_26950652CB305D73 ON historial (proveedor_id)');
        $this->addSql('ALTER TABLE producto CHANGE marca marca VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renglon_historial CHANGE detalle detalle VARCHAR(255) DEFAULT NULL, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE historial DROP FOREIGN KEY FK_26950652CB305D73');
        $this->addSql('DROP TABLE proveedor');
        $this->addSql('ALTER TABLE cliente CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono BIGINT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_26950652CB305D73 ON historial');
        $this->addSql('ALTER TABLE historial DROP proveedor_id');
        $this->addSql('ALTER TABLE producto CHANGE marca marca VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renglon_historial CHANGE detalle detalle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cantidad cantidad INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
