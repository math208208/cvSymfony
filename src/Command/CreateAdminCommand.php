<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un administrateur pour EasyAdmin',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new Admin();
        $admin->setEmail('test@test');
        $admin->setRoles(['ROLE_USER']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'infostrates'));

        $this->em->persist($admin);
        $this->em->flush();

        $output->writeln('✅ Admin créé avec succès.');
        return Command::SUCCESS;
    }
}

