<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909033809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE maquinaria (id INT AUTO_INCREMENT NOT NULL, cantidad INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maquinaria_receta (maquinaria_id INT NOT NULL, receta_id INT NOT NULL, INDEX IDX_FD58504FAA2E8BF (maquinaria_id), INDEX IDX_FD58504F54F853F8 (receta_id), PRIMARY KEY(maquinaria_id, receta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receta (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, descripcion LONGTEXT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_B093494EDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repuestos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, cantidad INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repuestos_receta (repuestos_id INT NOT NULL, receta_id INT NOT NULL, INDEX IDX_D52AF928F08A380E (repuestos_id), INDEX IDX_D52AF92854F853F8 (receta_id), PRIMARY KEY(repuestos_id, receta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, direccion VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, telefono VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maquinaria_receta ADD CONSTRAINT FK_FD58504FAA2E8BF FOREIGN KEY (maquinaria_id) REFERENCES maquinaria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maquinaria_receta ADD CONSTRAINT FK_FD58504F54F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receta ADD CONSTRAINT FK_B093494EDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE repuestos_receta ADD CONSTRAINT FK_D52AF928F08A380E FOREIGN KEY (repuestos_id) REFERENCES repuestos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repuestos_receta ADD CONSTRAINT FK_D52AF92854F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maquinaria_receta DROP FOREIGN KEY FK_FD58504FAA2E8BF');
        $this->addSql('ALTER TABLE maquinaria_receta DROP FOREIGN KEY FK_FD58504F54F853F8');
        $this->addSql('ALTER TABLE receta DROP FOREIGN KEY FK_B093494EDB38439E');
        $this->addSql('ALTER TABLE repuestos_receta DROP FOREIGN KEY FK_D52AF928F08A380E');
        $this->addSql('ALTER TABLE repuestos_receta DROP FOREIGN KEY FK_D52AF92854F853F8');
        $this->addSql('DROP TABLE maquinaria');
        $this->addSql('DROP TABLE maquinaria_receta');
        $this->addSql('DROP TABLE receta');
        $this->addSql('DROP TABLE repuestos');
        $this->addSql('DROP TABLE repuestos_receta');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
