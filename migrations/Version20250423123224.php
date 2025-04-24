<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423123224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE experience_pro_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT fk_f376f39b37000397
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experience_pro_translation
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE experience_pro_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE experience_pro_translation (id SERIAL NOT NULL, experience_pro_id INT NOT NULL, locale VARCHAR(5) NOT NULL, poste VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f376f39b37000397 ON experience_pro_translation (experience_pro_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD CONSTRAINT fk_f376f39b37000397 FOREIGN KEY (experience_pro_id) REFERENCES experience_pro (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
