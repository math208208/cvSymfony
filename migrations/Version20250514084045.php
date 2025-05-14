<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514084045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE professionnel ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE professionnel_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('professionnel_id_seq', (SELECT MAX(id) FROM professionnel))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professionnel ALTER id SET DEFAULT nextval('professionnel_id_seq')
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professionnel ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professionnel ALTER id DROP DEFAULT
        SQL);
    }
}
