<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423095329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT fk_f376f39b2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f376f39b2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT experience_pro_translation_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation RENAME COLUMN translatable_id TO experience_pro_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD CONSTRAINT FK_F376F39B37000397 FOREIGN KEY (experience_pro_id) REFERENCES experience_pro (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F376F39B37000397 ON experience_pro_translation (experience_pro_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD PRIMARY KEY (locale, experience_pro_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT FK_F376F39B37000397
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F376F39B37000397
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX experience_pro_translation_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation RENAME COLUMN experience_pro_id TO translatable_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD CONSTRAINT fk_f376f39b2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES experience_pro (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f376f39b2c2ac5d3 ON experience_pro_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD PRIMARY KEY (locale, translatable_id)
        SQL);
    }
}
