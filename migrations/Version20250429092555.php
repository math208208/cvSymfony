<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429092555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE competence ADD archived BOOLEAN DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro ADD archived BOOLEAN DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni ADD archived BOOLEAN DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD archived BOOLEAN DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ADD archived BOOLEAN DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil ADD archived BOOLEAN DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni DROP archived
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP archived
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro DROP archived
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence DROP archived
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage DROP archived
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil DROP archived
        SQL);
    }
}
