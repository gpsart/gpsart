<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201010132045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE activity (id VARCHAR(255) NOT NULL, route_id VARCHAR(255) NOT NULL, preview_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, total_duration INT NOT NULL, INDEX IDX_AC74095A34ECB4E6 (route_id), INDEX leaderboard_idx (total_duration), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE route (id VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, content LONGTEXT NOT NULL, distance INT NOT NULL, preview_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE activity ADD CONSTRAINT FK_AC74095A34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A34ECB4E6');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE route');
    }
}
