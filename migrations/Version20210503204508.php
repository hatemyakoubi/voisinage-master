<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503204508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_sous_categorie (demande_id INT NOT NULL, sous_categorie_id INT NOT NULL, INDEX IDX_99A509A680E95E18 (demande_id), INDEX IDX_99A509A6365BF48 (sous_categorie_id), PRIMARY KEY(demande_id, sous_categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_sous_categorie ADD CONSTRAINT FK_99A509A680E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_sous_categorie ADD CONSTRAINT FK_99A509A6365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demande_sous_categorie');
    }
}
