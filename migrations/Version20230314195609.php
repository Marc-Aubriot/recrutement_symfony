<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314195609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY annonces_ibfk_1');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY candidat_ibfk_1');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY candidatures_ibfk_1');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY candidatures_ibfk_2');
        $this->addSql('ALTER TABLE consultant DROP FOREIGN KEY consultant_ibfk_1');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY recruteur_ibfk_1');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE candidatures');
        $this->addSql('DROP TABLE consultant');
        $this->addSql('DROP TABLE recruteur');
        $this->addSql('ALTER TABLE utilisateur ADD utilisateur_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE ID id INT AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE prénom prénom VARCHAR(50) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (ID CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, recruteur_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, intitulé VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, nom_entreprise VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, adresse_entreprise VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_annonce DATETIME DEFAULT \'NULL\', validation_annonce TINYINT(1) DEFAULT NULL, INDEX recruteur_ID (recruteur_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE candidat (candidat_ID INT AUTO_INCREMENT NOT NULL, utilisateur_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, candidat_CV BLOB DEFAULT NULL, INDEX utilisateur_ID (utilisateur_ID), PRIMARY KEY(candidat_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE candidatures (candidature_ID CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, annonce_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, user_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, date_candidature DATETIME DEFAULT \'NULL\', validation_candidature TINYINT(1) DEFAULT NULL, INDEX annonce_ID (annonce_ID), INDEX user_ID (user_ID), PRIMARY KEY(candidature_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE consultant (consultant_ID INT AUTO_INCREMENT NOT NULL, utilisateur_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, INDEX utilisateur_ID (utilisateur_ID), PRIMARY KEY(consultant_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recruteur (recruteur_ID INT AUTO_INCREMENT NOT NULL, utilisateur_ID CHAR(36) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, nom_entreprise VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, adresse_entreprise VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, INDEX utilisateur_ID (utilisateur_ID), PRIMARY KEY(recruteur_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT annonces_ibfk_1 FOREIGN KEY (recruteur_ID) REFERENCES utilisateur (ID)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT candidat_ibfk_1 FOREIGN KEY (utilisateur_ID) REFERENCES utilisateur (ID)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT candidatures_ibfk_1 FOREIGN KEY (annonce_ID) REFERENCES annonces (ID)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT candidatures_ibfk_2 FOREIGN KEY (user_ID) REFERENCES utilisateur (ID)');
        $this->addSql('ALTER TABLE consultant ADD CONSTRAINT consultant_ibfk_1 FOREIGN KEY (utilisateur_ID) REFERENCES utilisateur (ID)');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT recruteur_ibfk_1 FOREIGN KEY (utilisateur_ID) REFERENCES utilisateur (ID)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE utilisateur DROP utilisateur_id, CHANGE id ID CHAR(36) NOT NULL, CHANGE nom nom VARCHAR(50) DEFAULT \'NULL\', CHANGE prénom prénom VARCHAR(50) DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(255) DEFAULT \'NULL\', CHANGE email email VARCHAR(100) DEFAULT \'NULL\', CHANGE password password VARCHAR(50) DEFAULT \'NULL\'');
    }
}
