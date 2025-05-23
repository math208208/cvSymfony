<?php

namespace App\Security\Voter;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

//permet de restreindre l'accé au autres page au non admin
final class ProfileVoter extends Voter
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['VIEW_PROFILE'])
            && is_string($subject);
    }

    protected function voteOnAttribute(string $attribute, $slug, TokenInterface $token): bool
    {
        $admin = $token->getUser();

        if (!$admin) {
            return false;
        }
        if (in_array('ROLE_ADMIN', $admin->getRoles()) || in_array('ROLE_PRO', $admin->getRoles())) {
            return true;
        }
        $email = $admin->getUserIdentifier();

        $user = $this->userRepository->findOneBy(['email' => $email]);


        if (!$user) {
            throw new \LogicException("Aucun utilisateur trouvé avec l'email : $email");
        }

        $slug = $user->getSlug();

        return $user->getSlug() === $slug;
    }
}
