<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429144828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation ADD trad_espagnol VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation DROP locale
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation RENAME COLUMN nom_langue TO trad_anglais
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation ADD locale VARCHAR(2) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation ADD nom_langue VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation DROP trad_anglais
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation DROP trad_espagnol
        SQL);
    }
}
