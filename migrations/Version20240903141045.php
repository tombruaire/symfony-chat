<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903141045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE messages (id INT NOT NULL, user_from_id INT NOT NULL, user_to_id INT DEFAULT NULL, content TEXT NOT NULL, date_envoi TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DB021E9620C3C701 ON messages (user_from_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96D2F7B13D ON messages (user_to_id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9620C3C701 FOREIGN KEY (user_from_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96D2F7B13D FOREIGN KEY (user_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE messages_id_seq CASCADE');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E9620C3C701');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E96D2F7B13D');
        $this->addSql('DROP TABLE messages');
    }
}
