<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219104628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visite_oeuvre (visiteur_id INT NOT NULL, oeuvre_id INT NOT NULL, INDEX IDX_8E49820F7F72333D (visiteur_id), INDEX IDX_8E49820F88194DE8 (oeuvre_id), PRIMARY KEY(visiteur_id, oeuvre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visite_oeuvre ADD CONSTRAINT FK_8E49820F7F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visite_oeuvre ADD CONSTRAINT FK_8E49820F88194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artiste ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE oeuvre ADD artiste_id INT NOT NULL, ADD description LONGTEXT NOT NULL, DROP artiste, DROP visiteurs');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT FK_35FE2EFE21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('CREATE INDEX IDX_35FE2EFE21D25844 ON oeuvre (artiste_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4EA587B8E7927C74 ON visiteur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visite_oeuvre DROP FOREIGN KEY FK_8E49820F7F72333D');
        $this->addSql('ALTER TABLE visite_oeuvre DROP FOREIGN KEY FK_8E49820F88194DE8');
        $this->addSql('DROP TABLE visite_oeuvre');
        $this->addSql('ALTER TABLE artiste DROP photo');
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY FK_35FE2EFE21D25844');
        $this->addSql('DROP INDEX IDX_35FE2EFE21D25844 ON oeuvre');
        $this->addSql('ALTER TABLE oeuvre ADD artiste VARCHAR(255) NOT NULL, ADD visiteurs VARCHAR(255) NOT NULL, DROP artiste_id, DROP description');
        $this->addSql('DROP INDEX UNIQ_4EA587B8E7927C74 ON visiteur');
    }
}
