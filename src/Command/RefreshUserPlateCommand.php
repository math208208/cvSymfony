<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:refresh-user-plate',
    description: 'Recréé la table user_plate en agrégant les données utilisateurs.',
)]
class RefreshUserPlateCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conn = $this->em->getConnection();

        $output->writeln('Suppression de la table user_plate si elle existe...');
        $conn->executeStatement('DROP TABLE IF EXISTS user_plate');

        $output->writeln('Création de la table user_plate...');
        $sql = <<<SQL
            CREATE TABLE user_plate AS
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
            WHERE u.is_private = false
            AND u.description IS NOT NULL
            AND u.profession IS NOT NULL
            GROUP BY u.id, u.nom, u.prenom, u.profession, u.description, u.email, u.slug;
            SQL;

        $conn->executeStatement($sql);

        $output->writeln('Table user_plate créée avec succès.');

        return Command::SUCCESS;
    }
}
