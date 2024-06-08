<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528084534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amis (idUtilisateur INT NOT NULL, idUtilisateurAmi INT NOT NULL, INDEX IDX_9FE2E7615D419CCB (idUtilisateur), INDEX IDX_9FE2E7612BF13F1F (idUtilisateurAmi), PRIMARY KEY(idUtilisateur, idUtilisateurAmi)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, dateCreationCommentaire DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, idCommentaireParent INT DEFAULT NULL, INDEX IDX_D9BEC0C45D419CCB (idUtilisateur), INDEX IDX_D9BEC0C46F756E12 (idRessource), INDEX IDX_D9BEC0C4517E5366 (idCommentaireParent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enregistrer (dateFavoris DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, INDEX IDX_94548385D419CCB (idUtilisateur), INDEX IDX_94548386F756E12 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, idUtilisateur INT NOT NULL, INDEX IDX_576366D95D419CCB (idUtilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes_ressources (idRessource INT NOT NULL, idGroupe INT NOT NULL, INDEX IDX_9B81C0086F756E12 (idRessource), INDEX IDX_9B81C00867A824BB (idGroupe), PRIMARY KEY(idRessource, idGroupe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupes_utilisateurs (idUtilisateur INT NOT NULL, idGroupe INT NOT NULL, INDEX IDX_68C4EF995D419CCB (idUtilisateur), INDEX IDX_68C4EF9967A824BB (idGroupe), PRIMARY KEY(idUtilisateur, idGroupe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, dateEnvoi DATETIME NOT NULL, idUtilisateurEnvoie INT NOT NULL, idUtilisateurRecois INT NOT NULL, INDEX IDX_DB021E96647863E9 (idUtilisateurEnvoie), INDEX IDX_DB021E969E687227 (idUtilisateurRecois), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partage (datePartage DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, INDEX IDX_8B929E6E5D419CCB (idUtilisateur), INDEX IDX_8B929E6E6F756E12 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participer (dateParticipation DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, INDEX IDX_EDBE16F85D419CCB (idUtilisateur), INDEX IDX_EDBE16F86F756E12 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, dateCreationRessource DATETIME NOT NULL, estPubliee TINYINT(1) DEFAULT 0 NOT NULL, estValidee TINYINT(1) DEFAULT 0 NOT NULL, estRestreinte TINYINT(1) DEFAULT 0 NOT NULL, estExploitee TINYINT(1) DEFAULT 0 NOT NULL, estArchivee TINYINT(1) DEFAULT 0 NOT NULL, estDesactivee TINYINT(1) DEFAULT 0 NOT NULL, multimedia VARCHAR(255) DEFAULT NULL, idUtilisateur INT NOT NULL, idCategorie INT NOT NULL, INDEX IDX_6A2CD5C75D419CCB (idUtilisateur), INDEX IDX_6A2CD5C7B597FD62 (idCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(255) NOT NULL, estDesactive TINYINT(1) DEFAULT 0 NOT NULL, photo VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E7615D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E7612BF13F1F FOREIGN KEY (idUtilisateurAmi) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C45D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C46F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4517E5366 FOREIGN KEY (idCommentaireParent) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enregistrer ADD CONSTRAINT FK_94548385D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enregistrer ADD CONSTRAINT FK_94548386F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes ADD CONSTRAINT FK_576366D95D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_ressources ADD CONSTRAINT FK_9B81C0086F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_ressources ADD CONSTRAINT FK_9B81C00867A824BB FOREIGN KEY (idGroupe) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_utilisateurs ADD CONSTRAINT FK_68C4EF995D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupes_utilisateurs ADD CONSTRAINT FK_68C4EF9967A824BB FOREIGN KEY (idGroupe) REFERENCES groupes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96647863E9 FOREIGN KEY (idUtilisateurEnvoie) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969E687227 FOREIGN KEY (idUtilisateurRecois) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E5D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E6F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F85D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F86F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C75D419CCB FOREIGN KEY (idUtilisateur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7B597FD62 FOREIGN KEY (idCategorie) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E7615D419CCB');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E7612BF13F1F');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C45D419CCB');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C46F756E12');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4517E5366');
        $this->addSql('ALTER TABLE enregistrer DROP FOREIGN KEY FK_94548385D419CCB');
        $this->addSql('ALTER TABLE enregistrer DROP FOREIGN KEY FK_94548386F756E12');
        $this->addSql('ALTER TABLE groupes DROP FOREIGN KEY FK_576366D95D419CCB');
        $this->addSql('ALTER TABLE groupes_ressources DROP FOREIGN KEY FK_9B81C0086F756E12');
        $this->addSql('ALTER TABLE groupes_ressources DROP FOREIGN KEY FK_9B81C00867A824BB');
        $this->addSql('ALTER TABLE groupes_utilisateurs DROP FOREIGN KEY FK_68C4EF995D419CCB');
        $this->addSql('ALTER TABLE groupes_utilisateurs DROP FOREIGN KEY FK_68C4EF9967A824BB');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96647863E9');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969E687227');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E5D419CCB');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E6F756E12');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F85D419CCB');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F86F756E12');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C75D419CCB');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7B597FD62');
        $this->addSql('DROP TABLE amis');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE enregistrer');
        $this->addSql('DROP TABLE groupes');
        $this->addSql('DROP TABLE groupes_ressources');
        $this->addSql('DROP TABLE groupes_utilisateurs');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE partage');
        $this->addSql('DROP TABLE participer');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
