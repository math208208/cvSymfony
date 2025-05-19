<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class UserPlateUpdater
{
    private Connection $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function refreshUserPlate(int $userId): void
    {
        $this->conn->executeStatement('DELETE FROM user_plate WHERE id = :id', ['id' => $userId]);

        $sql = <<<SQL
        INSERT INTO user_plate (
            id, nom, prenom, profession, description, email, slug,
            formations, experiences_pro, experiences_uni,
            langages, outils, loisirs, competences
        )
        SELECT
            u.id,
            u.nom,
            u.prenom,
            u.profession,
            u.description,
            u.email,
            u.slug,
            u.is_private,
            COALESCE(string_agg(DISTINCT f.intitule, ' '), '') AS formations,
            COALESCE(string_agg(DISTINCT ep.poste || ' ' || ep.entreprise || ' ' || ep.description, ' '), '')
            AS experiences_pro,
            COALESCE(string_agg(DISTINCT eu.titre || ' ' || eu.description, ' '), '') AS experiences_uni,
            COALESCE(string_agg(DISTINCT l.nom_langue, ' '), '') AS langages,
            COALESCE(string_agg(DISTINCT o.nom, ' '), '') AS outils,
            COALESCE(string_agg(DISTINCT lo.nom, ' '), '') AS loisirs,
            COALESCE(string_agg(DISTINCT c.nom, ' '), '') AS competences
        FROM "user" u
        LEFT JOIN formation f ON f.user_id = u.id
        LEFT JOIN experience_pro ep ON ep.user_id = u.id
        LEFT JOIN experience_uni eu ON eu.user_id = u.id
        LEFT JOIN langage l ON l.user_id = u.id
        LEFT JOIN outil o ON o.user_id = u.id
        LEFT JOIN loisir lo ON lo.user_id = u.id
        LEFT JOIN competence c ON c.user_id = u.id
        WHERE u.id = :id
        GROUP BY u.id, u.nom, u.prenom, u.profession, u.description, u.email, u.slug;
        SQL;

        $this->conn->executeStatement($sql, ['id' => $userId]);
    }


    public function deleteUserPlate(int $userId): void
    {
        $this->conn->executeStatement('DELETE FROM user_plate WHERE id = :id', ['id' => $userId]);
    }
}
