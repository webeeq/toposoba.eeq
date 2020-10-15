<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014125102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, province_id SMALLINT UNSIGNED DEFAULT NULL, active TINYINT(1) NOT NULL, name VARCHAR(30) NOT NULL, INDEX IDX_2D5B0234E946114A (province_id), INDEX name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, name VARCHAR(30) NOT NULL, INDEX name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, province_id SMALLINT UNSIGNED DEFAULT NULL, city_id SMALLINT UNSIGNED DEFAULT NULL, active TINYINT(1) NOT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(50) NOT NULL, description TEXT NOT NULL, ranking INT UNSIGNED NOT NULL, number INT UNSIGNED NOT NULL, ip VARCHAR(15) NOT NULL, date DATETIME NOT NULL, INDEX IDX_8D93D649E946114A (province_id), INDEX IDX_8D93D6498BAC62AF (city_id), INDEX name (name), INDEX surname (surname), INDEX ranking (ranking), INDEX number (number), INDEX date (date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234E946114A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E946114A');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE user');
    }
}
