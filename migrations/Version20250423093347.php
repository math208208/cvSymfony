<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423093347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE experience_pro_translation (locale VARCHAR(5) NOT NULL, translatable_id INT NOT NULL, poste VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(locale, translatable_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F376F39B2C2AC5D3 ON experience_pro_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD CONSTRAINT FK_F376F39B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES experience_pro (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT FK_F376F39B2C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experience_pro_translation
        SQL);
    }
}
