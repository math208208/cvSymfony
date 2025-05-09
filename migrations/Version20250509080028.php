<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509080028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ALTER user_id DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil ALTER id TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation ALTER id TYPE VARCHAR(255)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE outil ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage ALTER user_id SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competence ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro ALTER id TYPE INT
        SQL);
    }
}
