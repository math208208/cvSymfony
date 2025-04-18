<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418092729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE competence ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence ADD CONSTRAINT FK_94D4687FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_94D4687FA76ED395 ON competence (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ADD CONSTRAINT FK_CC50EA26A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CC50EA26A76ED395 ON langage (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil ADD CONSTRAINT FK_22627A3EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_22627A3EA76ED395 ON outil (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage DROP CONSTRAINT FK_CC50EA26A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CC50EA26A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil DROP CONSTRAINT FK_22627A3EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_22627A3EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence DROP CONSTRAINT FK_94D4687FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_94D4687FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence DROP user_id
        SQL);
    }
}
