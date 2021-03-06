<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824165033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief_groupe DROP FOREIGN KEY FK_5496297B757FABFF');
        $this->addSql('ALTER TABLE brief_groupe DROP FOREIGN KEY FK_5496297B7A45358C');
        $this->addSql('ALTER TABLE brief_groupe ADD id INT AUTO_INCREMENT NOT NULL, ADD statut VARCHAR(255) NOT NULL, CHANGE brief_id brief_id INT DEFAULT NULL, CHANGE groupe_id groupe_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE brief_groupe ADD CONSTRAINT FK_5496297B757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE brief_groupe ADD CONSTRAINT FK_5496297B7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief_groupe MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE brief_groupe DROP FOREIGN KEY FK_5496297B7A45358C');
        $this->addSql('ALTER TABLE brief_groupe DROP FOREIGN KEY FK_5496297B757FABFF');
        $this->addSql('ALTER TABLE brief_groupe DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE brief_groupe DROP id, DROP statut, CHANGE groupe_id groupe_id INT NOT NULL, CHANGE brief_id brief_id INT NOT NULL');
        $this->addSql('ALTER TABLE brief_groupe ADD CONSTRAINT FK_5496297B7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_groupe ADD CONSTRAINT FK_5496297B757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_groupe ADD PRIMARY KEY (brief_id, groupe_id)');
    }
}
