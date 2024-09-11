<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911122640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `categories` (`id`, `nom`) VALUES (1, "Technologie"), (2, "Science"), (3, "Cuisine"), (4, "Voyage"), (5, "Musique"), (6, "Sport"), (7, "Art"), (8, "Mode"), (9, "Santé"), (10, "Histoire"), (11, "Éducation"), (12, "Littérature"), (13, "Finance"), (14, "Photographie"), (15, "Nature"), (16, "Divertissement"), (17, "Religion"), (18, "Psychologie"), (19, "Design"), (20, "Cinéma");');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
