<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821234514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_de_sorti (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, isdeleted TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_brief_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, promobrief_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_A9D0C93CC5697D6D (apprenant_id), INDEX IDX_A9D0C93C943F2B0 (promobrief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistiques_competences (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, referentiel_id INT DEFAULT NULL, competence_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_5C1C9F22C5697D6D (apprenant_id), INDEX IDX_5C1C9F22805DB139 (referentiel_id), INDEX IDX_5C1C9F2215761DAB (competence_id), INDEX IDX_5C1C9F22D0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_brief_apprenant ADD CONSTRAINT FK_A9D0C93CC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promo_brief_apprenant ADD CONSTRAINT FK_A9D0C93C943F2B0 FOREIGN KEY (promobrief_id) REFERENCES promo_brief (id)');
        $this->addSql('ALTER TABLE statistiques_competences ADD CONSTRAINT FK_5C1C9F22C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE statistiques_competences ADD CONSTRAINT FK_5C1C9F22805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE statistiques_competences ADD CONSTRAINT FK_5C1C9F2215761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE statistiques_competences ADD CONSTRAINT FK_5C1C9F22D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE user ADD profil_de_sorti_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498126E894 FOREIGN KEY (profil_de_sorti_id) REFERENCES profil_de_sorti (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498126E894 ON user (profil_de_sorti_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498126E894');
        $this->addSql('DROP TABLE profil_de_sorti');
        $this->addSql('DROP TABLE promo_brief_apprenant');
        $this->addSql('DROP TABLE statistiques_competences');
        $this->addSql('DROP INDEX IDX_8D93D6498126E894 ON user');
        $this->addSql('ALTER TABLE user DROP profil_de_sorti_id');
    }
}
