<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810014348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, isdeleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_groupe_tag (groupe_tag_source INT NOT NULL, groupe_tag_target INT NOT NULL, INDEX IDX_4E73E8E6E260783 (groupe_tag_source), INDEX IDX_4E73E8E617C3570C (groupe_tag_target), PRIMARY KEY(groupe_tag_source, groupe_tag_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, isdeleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_tag_groupe_tag ADD CONSTRAINT FK_4E73E8E6E260783 FOREIGN KEY (groupe_tag_source) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_groupe_tag ADD CONSTRAINT FK_4E73E8E617C3570C FOREIGN KEY (groupe_tag_target) REFERENCES groupe_tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_tag_groupe_tag DROP FOREIGN KEY FK_4E73E8E6E260783');
        $this->addSql('ALTER TABLE groupe_tag_groupe_tag DROP FOREIGN KEY FK_4E73E8E617C3570C');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_groupe_tag');
        $this->addSql('DROP TABLE tag');
    }
}
