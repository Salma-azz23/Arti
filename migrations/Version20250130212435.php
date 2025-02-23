<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130212435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, biographie LONGTEXT NOT NULL, date_naissance DATE NOT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE artiste_visiteur (artiste_id INT NOT NULL, visiteur_id INT NOT NULL, INDEX IDX_360BBA121D25844 (artiste_id), INDEX IDX_360BBA17F72333D (visiteur_id), PRIMARY KEY(artiste_id, visiteur_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE oeuvre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) DEFAULT NULL, artiste_id INT DEFAULT NULL, INDEX IDX_35FE2EFE21D25844 (artiste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE visiteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4EA587B8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE artiste_visiteur ADD CONSTRAINT FK_360BBA121D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artiste_visiteur ADD CONSTRAINT FK_360BBA17F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT FK_35FE2EFE21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('ALTER TABLE visite_oeuvre ADD CONSTRAINT FK_8E49820F7F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visite_oeuvre ADD CONSTRAINT FK_8E49820F88194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artiste_visiteur DROP FOREIGN KEY FK_360BBA121D25844');
        $this->addSql('ALTER TABLE artiste_visiteur DROP FOREIGN KEY FK_360BBA17F72333D');
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY FK_35FE2EFE21D25844');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE artiste_visiteur');
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visiteur');
        $this->addSql('ALTER TABLE visite_oeuvre DROP FOREIGN KEY FK_8E49820F7F72333D');
        $this->addSql('ALTER TABLE visite_oeuvre DROP FOREIGN KEY FK_8E49820F88194DE8');
    }
}
