<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914202744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_vinyl (genre_id INT NOT NULL, vinyl_id INT NOT NULL, INDEX IDX_B3284CD84296D31F (genre_id), INDEX IDX_B3284CD83FFFF645 (vinyl_id), PRIMARY KEY(genre_id, vinyl_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_artist (genre_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_EAEAA6A74296D31F (genre_id), INDEX IDX_EAEAA6A7B7970CF8 (artist_id), PRIMARY KEY(genre_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_album (genre_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_849E71864296D31F (genre_id), INDEX IDX_849E71861137ABCF (album_id), PRIMARY KEY(genre_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre_vinyl ADD CONSTRAINT FK_B3284CD84296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_vinyl ADD CONSTRAINT FK_B3284CD83FFFF645 FOREIGN KEY (vinyl_id) REFERENCES vinyl (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_artist ADD CONSTRAINT FK_EAEAA6A74296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_artist ADD CONSTRAINT FK_EAEAA6A7B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_album ADD CONSTRAINT FK_849E71864296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_album ADD CONSTRAINT FK_849E71861137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE artist ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vinyl ADD photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre_vinyl DROP FOREIGN KEY FK_B3284CD84296D31F');
        $this->addSql('ALTER TABLE genre_artist DROP FOREIGN KEY FK_EAEAA6A74296D31F');
        $this->addSql('ALTER TABLE genre_album DROP FOREIGN KEY FK_849E71864296D31F');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_vinyl');
        $this->addSql('DROP TABLE genre_artist');
        $this->addSql('DROP TABLE genre_album');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE album DROP photo');
        $this->addSql('ALTER TABLE artist DROP photo');
        $this->addSql('ALTER TABLE vinyl DROP photo');
    }
}
