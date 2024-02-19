<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216142851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amis (idUtilisateur INT NOT NULL, idUtilisateurAmi INT NOT NULL, INDEX IDX_9FE2E7615D419CCB (idUtilisateur), INDEX IDX_9FE2E7612BF13F1F (idUtilisateurAmi), PRIMARY KEY(idUtilisateur, idUtilisateurAmi)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E7615D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E7612BF13F1F FOREIGN KEY (idUtilisateurAmi) REFERENCES utilisateurs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E7615D419CCB');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E7612BF13F1F');
        $this->addSql('DROP TABLE amis');
    }
}
