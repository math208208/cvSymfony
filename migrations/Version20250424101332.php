<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424101332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE formation_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EB9465B42C2AC5D3 ON formation_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE langage_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, nom_langue VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3FA504BE2C2AC5D3 ON langage_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loisir_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B377A1182C2AC5D3 ON loisir_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, profession VARCHAR(255) DEFAULT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D728CFA2C2AC5D3 ON user_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation_translation ADD CONSTRAINT FK_EB9465B42C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation ADD CONSTRAINT FK_3FA504BE2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES langage (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir_translation ADD CONSTRAINT FK_B377A1182C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES loisir (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_translation ADD CONSTRAINT FK_1D728CFA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation_translation DROP CONSTRAINT FK_EB9465B42C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation DROP CONSTRAINT FK_3FA504BE2C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir_translation DROP CONSTRAINT FK_B377A1182C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_translation DROP CONSTRAINT FK_1D728CFA2C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE langage_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loisir_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_translation
        SQL);
    }
}
