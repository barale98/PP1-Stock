<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
    final class Version20240910000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Permite valores nulos para el campo nombre en la tabla user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY nombre VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY nombre VARCHAR(255) NOT NULL');
    }
}