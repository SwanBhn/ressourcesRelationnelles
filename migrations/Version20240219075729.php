<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219075729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enregistrer (idUtilisateur INT NOT NULL, idRessource INT NOT NULL, dateFavoris DATETIME NOT NULL, INDEX IDX_9454838C6EE5C49 (idUtilisateur), INDEX IDX_9454838DFADA058 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupesRessources (idRessource INT NOT NULL, idGroupe INT NOT NULL, INDEX IDX_9B81C008DFADA058 (idRessource), INDEX IDX_9B81C008FA7089AB (idGroupe), PRIMARY KEY(idRessource, idGroupe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupesUtilisateurs (idUtilisateur INT NOT NULL, idGroupe INT NOT NULL, INDEX IDX_68C4EF99C6EE5C49 (idUtilisateur), INDEX IDX_68C4EF99FA7089AB (idGroupe), PRIMARY KEY(idUtilisateur, idGroupe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partage (datePartage DATETIME NOT NULL, idUtilisateur INT NOT NULL, idRessource INT NOT NULL, INDEX IDX_8B929E6E5D419CCB (idUtilisateur), INDEX IDX_8B929E6E6F756E12 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participer (idUtilisateur INT NOT NULL, idRessource INT NOT NULL, dateParticipation DATETIME NOT NULL, INDEX IDX_EDBE16F8C6EE5C49 (idUtilisateur), INDEX IDX_EDBE16F8DFADA058 (idRessource), PRIMARY KEY(idUtilisateur, idRessource)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enregistrer ADD CONSTRAINT FK_9454838C6EE5C49 FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE enregistrer ADD CONSTRAINT FK_9454838DFADA058 FOREIGN KEY (idRessource) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE groupesRessources ADD CONSTRAINT FK_9B81C008DFADA058 FOREIGN KEY (idRessource) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE groupesRessources ADD CONSTRAINT FK_9B81C008FA7089AB FOREIGN KEY (idGroupe) REFERENCES groupes (id)');
        $this->addSql('ALTER TABLE groupesUtilisateurs ADD CONSTRAINT FK_68C4EF99C6EE5C49 FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE groupesUtilisateurs ADD CONSTRAINT FK_68C4EF99FA7089AB FOREIGN KEY (idGroupe) REFERENCES groupes (id)');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E5D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E6F756E12 FOREIGN KEY (idRessource) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F8C6EE5C49 FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F8DFADA058 FOREIGN KEY (idRessource) REFERENCES ressources (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enregistrer DROP FOREIGN KEY FK_9454838C6EE5C49');
        $this->addSql('ALTER TABLE enregistrer DROP FOREIGN KEY FK_9454838DFADA058');
        $this->addSql('ALTER TABLE groupes_ressources DROP FOREIGN KEY FK_9B81C008DFADA058');
        $this->addSql('ALTER TABLE groupes_ressources DROP FOREIGN KEY FK_9B81C008FA7089AB');
        $this->addSql('ALTER TABLE groupes_utilisateurs DROP FOREIGN KEY FK_68C4EF99C6EE5C49');
        $this->addSql('ALTER TABLE groupes_utilisateurs DROP FOREIGN KEY FK_68C4EF99FA7089AB');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E5D419CCB');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E6F756E12');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F8C6EE5C49');
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F8DFADA058');
        $this->addSql('DROP TABLE enregistrer');
        $this->addSql('DROP TABLE groupes_ressources');
        $this->addSql('DROP TABLE groupes_utilisateurs');
        $this->addSql('DROP TABLE partage');
        $this->addSql('DROP TABLE participer');
    }
}
