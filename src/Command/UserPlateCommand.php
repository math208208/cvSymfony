<?php
namespace App\Command;

use App\Entity\UserPlate;
use App\Repository\UserRepository;
use App\Service\ElasticService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:users-plate')]
class UserPlateCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            // Supprime les anciennes données
            $this->em->createQuery('DELETE FROM App\Entity\UserPlate up WHERE up.userId = :userId')
                ->setParameter('userId', $user->getId())
                ->execute();

            $fields = [
                ['profession', $user->getProfession()],
                ['description', $user->getDescription()],
                ['nom', $user->getNom()],
                ['prenom', $user->getPrenom()],
            ];

            foreach ($fields as [$field, $value]) {
                if ($value) {
                    $up = new UserPlate();
                    $up->setUserId($user->getId());
                    $up->setField($field);
                    $up->setI(0);
                    $up->setValue($value);
                    $this->em->persist($up);
                }
            }

            // Champs multiples
            foreach ($user->getCompetences() as $index => $comp) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('competence');
                $up->setI($index);
                $up->setValue($comp->getNom());
                $this->em->persist($up);
            }

            foreach ($user->getOutils() as $index => $outil) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('outil');
                $up->setI($index);
                $up->setValue($outil->getNom());
                $this->em->persist($up);
            }

            foreach ($user->getLangues() as $index => $langue) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('langue');
                $up->setI($index);
                $up->setValue($langue->getNomLangue());
                $this->em->persist($up);
            }

            foreach ($user->getLoisirs() as $index => $loisir) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('loisir');
                $up->setI($index);
                $up->setValue($loisir->getNom());
                $this->em->persist($up);
            }

            foreach ($user->getExperiencesPro() as $index => $exp) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('experience_pro');
                $up->setI($index);
                $up->setValue($exp->getPoste() . ' - ' . $exp->getEntreprise());
                $this->em->persist($up);
            }

            foreach ($user->getExperiencesUni() as $index => $exp) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('experience_uni');
                $up->setI($index);
                $up->setValue($exp->getTitre());
                $this->em->persist($up);
            }

            foreach ($user->getFormations() as $index => $formation) {
                $up = new UserPlate();
                $up->setUserId($user->getId());
                $up->setField('formation');
                $up->setI($index);
                $up->setValue($formation->getIntitule());
                $this->em->persist($up);
            }
        }

        $this->em->flush();

        $output->writeln('UserPlate table has been updated successfully.');

        return Command::SUCCESS;
    }
}
