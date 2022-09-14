<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220914132450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populate master data and sample data to tables';
    }

    public function up(Schema $schema): void
    {
        // Add data to ram table
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR3', 1)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR3', 2)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR3', 4)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR3', 8)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR4', 1)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR4', 2)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR4', 4)");
        $this->addSql("INSERT INTO ram (type, size) VALUES ('DDR4', 8)");

        // Add data to server table
        $this->addSql("INSERT INTO server (asset_id, brand, name, price) VALUES (123456789, 'Dell', 'R210', 500.50)");
        $this->addSql("INSERT INTO server (asset_id, brand, name, price) VALUES (123456799, 'HP', 'H555', 670.50)");

        // Add data to server_ram table
        $this->addSql("INSERT INTO server_ram (server_id, ram_id, quantity) VALUES (1, 1, 1)");
        $this->addSql("INSERT INTO server_ram (server_id, ram_id, quantity) VALUES (1, 2, 2)");
        $this->addSql("INSERT INTO server_ram (server_id, ram_id, quantity) VALUES (2, 7, 2)");
        $this->addSql("INSERT INTO server_ram (server_id, ram_id, quantity) VALUES (2, 8, 1)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE server_ram DROP FOREIGN KEY FK_D91828751844E6B7');
        $this->addSql('ALTER TABLE server_ram DROP FOREIGN KEY FK_D91828753366068');
        $this->addSql('TRUNCATE TABLE ram');
        $this->addSql('TRUNCATE TABLE server');
        $this->addSql('TRUNCATE TABLE server_ram');
    }
}
