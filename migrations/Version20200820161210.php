<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820161210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_de_sorti DROP FOREIGN KEY FK_FC43799FC5697D6D');
        $this->addSql('DROP INDEX IDX_FC43799FC5697D6D ON profil_de_sorti');
        $this->addSql('ALTER TABLE profil_de_sorti DROP apprenant_id, CHANGE isdeleted isdeleted TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD profil_de_sorti_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498126E894 FOREIGN KEY (profil_de_sorti_id) REFERENCES profil_de_sorti (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498126E894 ON user (profil_de_sorti_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_de_sorti ADD apprenant_id INT DEFAULT NULL, CHANGE isdeleted isdeleted VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE profil_de_sorti ADD CONSTRAINT FK_FC43799FC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FC43799FC5697D6D ON profil_de_sorti (apprenant_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498126E894');
        $this->addSql('DROP INDEX IDX_8D93D6498126E894 ON user');
        $this->addSql('ALTER TABLE user DROP profil_de_sorti_id');
    }
}
