<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210603212001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_offre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, offres_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, content LONGTEXT NOT NULL, boolean VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_61C1E9EA76ED395 (user_id), INDEX IDX_61C1E9E6C83CD9F (offres_id), INDEX IDX_61C1E9E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_offre ADD CONSTRAINT FK_61C1E9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_offre ADD CONSTRAINT FK_61C1E9E6C83CD9F FOREIGN KEY (offres_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE comment_offre ADD CONSTRAINT FK_61C1E9E727ACA70 FOREIGN KEY (parent_id) REFERENCES comment_offre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_offre DROP FOREIGN KEY FK_61C1E9E727ACA70');
        $this->addSql('DROP TABLE comment_offre');
    }
}
