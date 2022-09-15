<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220914132449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create database tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ram (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, asset_id INT NOT NULL UNIQUE, brand VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_ram (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, ram_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_D91828751844E6B7 (server_id), INDEX IDX_D91828753366068 (ram_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server_ram ADD CONSTRAINT FK_D91828751844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_ram ADD CONSTRAINT FK_D91828753366068 FOREIGN KEY (ram_id) REFERENCES ram (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE server_ram DROP FOREIGN KEY FK_D91828751844E6B7');
        $this->addSql('ALTER TABLE server_ram DROP FOREIGN KEY FK_D91828753366068');
        $this->addSql('DROP TABLE ram');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE server_ram');
    }
}
