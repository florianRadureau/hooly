<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404062031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD days_of_the_week_not_available TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD dates_not_available TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE location DROP day_of_the_week_not_available');
        $this->addSql('ALTER TABLE location DROP date_not_available');
        $this->addSql('COMMENT ON COLUMN location.days_of_the_week_not_available IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN location.dates_not_available IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE location ADD day_of_the_week_not_available TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD date_not_available TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE location DROP days_of_the_week_not_available');
        $this->addSql('ALTER TABLE location DROP dates_not_available');
        $this->addSql('COMMENT ON COLUMN location.day_of_the_week_not_available IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN location.date_not_available IS \'(DC2Type:array)\'');
    }
}
