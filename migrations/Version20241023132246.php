<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023132246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weather_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, date DATETIME NOT NULL, temperature NUMERIC(5, 2) NOT NULL, feels_like_temperature NUMERIC(10, 2) NOT NULL, humidity NUMERIC(10, 2) NOT NULL, precipitation NUMERIC(5, 2) NOT NULL, wind_speed NUMERIC(5, 2) NOT NULL, CONSTRAINT FK_266D77264D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_266D77264D218E ON weather_entry (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE weather_entry');
    }
}
