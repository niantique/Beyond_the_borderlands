<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708130640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, stop_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C3902063D (stop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stop (id INT AUTO_INCREMENT NOT NULL, trip_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, location VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_B95616B6A5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3902063D FOREIGN KEY (stop_id) REFERENCES stop (id)');
        $this->addSql('ALTER TABLE stop ADD CONSTRAINT FK_B95616B6A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3902063D');
        $this->addSql('ALTER TABLE stop DROP FOREIGN KEY FK_B95616B6A5BC2E0E');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE stop');
    }
}
