<?php

namespace App\Security\Voter;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProfileVoter extends Voter
{

    public function __construct(
        private UserRepository $userRepository
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['VIEW_PROFILE'])
            && is_string($subject); 
    }

    protected function voteOnAttribute(string $attribute, $slug, TokenInterface $token): bool
    {
        $admin = $token->getUser();
        
        if(!$admin){
            return false;
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
