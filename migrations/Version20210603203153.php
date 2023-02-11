<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210603203153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD offres_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6C83CD9F FOREIGN KEY (offres_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A6C83CD9F ON comments (offres_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6C83CD9F');
        $this->addSql('DROP INDEX IDX_5F9E962A6C83CD9F ON comments');
        $this->addSql('ALTER TABLE comments DROP offres_id');
    }
}
