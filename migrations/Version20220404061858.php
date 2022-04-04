<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404061858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE truck_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, day_of_the_week_not_available TEXT DEFAULT NULL, date_not_available TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN location.day_of_the_week_not_available IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN location.date_not_available IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, truck_id INT NOT NULL, location_id INT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955C6957CCE ON reservation (truck_id)');
        $this->addSql('CREATE INDEX IDX_42C8495564D218E ON reservation (location_id)');
        $this->addSql('CREATE TABLE truck (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495564D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C8495564D218E');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955C6957CCE');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE truck_id_seq CASCADE');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE truck');
    }
}
