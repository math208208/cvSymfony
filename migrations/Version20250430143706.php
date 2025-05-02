<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430143706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE experience_pro_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE experience_uni_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE formation_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE langage_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE loisir_translation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_translation DROP CONSTRAINT fk_1d728cfa2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation_translation DROP CONSTRAINT fk_eb9465b42c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation DROP CONSTRAINT fk_f376f39b2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir_translation DROP CONSTRAINT fk_b377a1182c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation DROP CONSTRAINT fk_3fa504be2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni_translation DROP CONSTRAINT fk_2c533f8c2c2ac5d3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experience_pro_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loisir_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE langage_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE experience_uni_translation
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE experience_pro_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE experience_uni_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE formation_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE langage_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE loisir_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, profession VARCHAR(255) DEFAULT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_1d728cfa2c2ac5d3 ON user_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formation_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_eb9465b42c2ac5d3 ON formation_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE experience_pro_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, poste VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f376f39b2c2ac5d3 ON experience_pro_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loisir_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_b377a1182c2ac5d3 ON loisir_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE langage_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, nom_langue VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_3fa504be2c2ac5d3 ON langage_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE experience_uni_translation (id SERIAL NOT NULL, translatable_id INT NOT NULL, locale VARCHAR(2) NOT NULL, titre VARCHAR(255) NOT NULL, sous_titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_2c533f8c2c2ac5d3 ON experience_uni_translation (translatable_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_translation ADD CONSTRAINT fk_1d728cfa2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation_translation ADD CONSTRAINT fk_eb9465b42c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_pro_translation ADD CONSTRAINT fk_f376f39b2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES experience_pro (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loisir_translation ADD CONSTRAINT fk_b377a1182c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES loisir (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE langage_translation ADD CONSTRAINT fk_3fa504be2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES langage (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experience_uni_translation ADD CONSTRAINT fk_2c533f8c2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES experience_uni (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
