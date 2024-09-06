<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240906073310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE amis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE amis (id INT NOT NULL, demandeur INT NOT NULL, cible INT NOT NULL, demande VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE amis_id_seq CASCADE');
        $this->addSql('DROP TABLE amis');
        $this->addSql('ALTER TABLE "user" ALTER online SET DEFAULT false');
        $this->addSql('ALTER TABLE "user" ALTER online DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER twofa SET DEFAULT false');
        $this->addSql('ALTER TABLE "user" ALTER twofa DROP NOT NULL');
    }
}
