<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603133846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD commentaire_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFAC8564B FOREIGN KEY (commentaire_id_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DFAC8564B ON post (commentaire_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFAC8564B');
        $this->addSql('DROP INDEX IDX_5A8A6C8DFAC8564B ON post');
        $this->addSql('ALTER TABLE post DROP commentaire_id_id');
    }
}
