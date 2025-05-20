<?php
namespace App\Command;

use App\Repository\UserRepository;
use App\Service\ElasticService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:index-users')]
class IndexUsersCommand extends Command
{
    private $userRepository;
    private $elasticService;

    public function __construct(UserRepository $userRepository, ElasticService $elasticService)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->elasticService = $elasticService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            try {
                $this->elasticService->index('users', (string) $user->getId(), [
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'profession' => $user->getProfession(),
                    'description' => $user->getDescription(),
                    'private' => $user->isPrivate(),
                    'formations' => $user->getFormationsString(), 
                    'experiences_pro' => $user->getExperiencesProString(),
                    'experiences_uni' => $user->getExperiencesUniString(),
                    'langages' => $user->getLangagesString(),
                    'outils' => $user->getOutilsString(),
                    'loisirs' => $user->getLoisirsString(),
                    'competences' => $user->getCompetencesString(),
                ]);
            } catch (\Throwable $e) {
                $output->writeln("<error>Erreur utilisateur #" . $user->getId() . " : " . $e->getMessage() . "</error>");
            }
        }

        $output->writeln('Utilisateurs indexés.');
        return Command::SUCCESS;
    }
}