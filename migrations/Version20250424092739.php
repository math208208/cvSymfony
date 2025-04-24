<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424092739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE experience_uni_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, titre VARCHAR(255) NOT NULL, sous_titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C533F8C2C2AC5D3 ON experience_uni_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni_translation ADD CONSTRAINT FK_2C533F8C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES experience_uni (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni_translation DROP CONSTRAINT FK_2C533F8C2C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experience_uni_translation
        SQL);
    }
}
