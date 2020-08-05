<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805202645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_competences ADD isdeleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE niveau ADD isdeleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE referentiel ADD isdeleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_competences DROP isdeleted');
        $this->addSql('ALTER TABLE niveau DROP isdeleted');
        $this->addSql('ALTER TABLE referentiel DROP isdeleted');
    }
}
