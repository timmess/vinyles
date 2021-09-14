<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913201907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vinyl ADD artist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vinyl ADD CONSTRAINT FK_E2E531DB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('CREATE INDEX IDX_E2E531DB7970CF8 ON vinyl (artist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinyl DROP FOREIGN KEY FK_E2E531DB7970CF8');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP INDEX IDX_E2E531DB7970CF8 ON vinyl');
        $this->addSql('ALTER TABLE vinyl DROP artist_id');
    }
}
