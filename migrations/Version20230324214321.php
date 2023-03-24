<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324214321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE recruteur_id recruteur_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE candidature CHANGE annonce_id annonce_id VARCHAR(255) NOT NULL, CHANGE user_id user_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE recruteur_id recruteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidature CHANGE annonce_id annonce_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
    }
}
