<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216131026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, idCommentaireParent INT DEFAULT NULL, INDEX IDX_D9BEC0C45D419CCB (idUtilisateur), INDEX IDX_D9BEC0C46F756E12 (idRessource), INDEX IDX_D9BEC0C4517E5366 (idCommentaireParent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, idUtilisateur INT NOT NULL, INDEX IDX_576366D95D419CCB (idUtilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_envoi DATETIME NOT NULL, idUtilisateurEnvoie INT NOT NULL, idUtilisateurRecois INT NOT NULL, INDEX IDX_DB021E96647863E9 (idUtilisateurEnvoie), INDEX IDX_DB021E969E687227 (idUtilisateurRecois), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, est_publiee TINYINT(1) DEFAULT 0 NOT NULL, est_validee TINYINT(1) DEFAULT 0 NOT NULL, est_restreinte TINYINT(1) DEFAULT 0 NOT NULL, est_exploitee TINYINT(1) DEFAULT 0 NOT NULL, est_archivee TINYINT(1) DEFAULT 0 NOT NULL, est_desactivee TINYINT(1) DEFAULT 0 NOT NULL, multimedia VARCHAR(255) DEFAULT NULL, idUtilisateur INT NOT NULL, idCategorie INT NOT NULL, INDEX IDX_6A2CD5C75D419CCB (idUtilisateur), INDEX IDX_6A2CD5C7B597FD62 (idCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, est_desactive TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C46F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4517E5366 FOREIGN KEY (idCommentaireParent) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D95D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96647863E9 FOREIGN KEY (idUtilisateurEnvoie) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969E687227 FOREIGN KEY (idUtilisateurRecois) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C75D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7B597FD62 FOREIGN KEY (idCategorie) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C45D419CCB');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C46F756E12');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4517E5366');
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D95D419CCB');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96647863E9');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969E687227');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C75D419CCB');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7B597FD62');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE groupes');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
