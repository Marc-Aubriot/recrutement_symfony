<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321203533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature ADD user_nom VARCHAR(50) DEFAULT NULL, ADD user_prenom VARCHAR(50) DEFAULT NULL, ADD user_mail VARCHAR(255) DEFAULT NULL, ADD user_is_valid TINYINT(1) DEFAULT NULL, ADD annonce_title VARCHAR(255) DEFAULT NULL, ADD annonce_nom_entreprise VARCHAR(255) DEFAULT NULL, ADD annonce_date DATE DEFAULT NULL, ADD annonce_is_valid TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP user_nom, DROP user_prenom, DROP user_mail, DROP user_is_valid, DROP annonce_title, DROP annonce_nom_entreprise, DROP annonce_date, DROP annonce_is_valid');
    }
}
