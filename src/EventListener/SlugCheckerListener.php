<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SlugCheckerListener
{
    private $security;

    public function __construct(
        Security $security,
        private UserRepository $userRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $slug = $request->attributes->get('slug');

        if (!$slug) {
            return;
        }

        if ($slug === "register") {
            return;
        }

        $admin = $this->security->getUser();
        if (!$admin) {
            return;
        }

        $email = $admin->getUserIdentifier();
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (in_array('ROLE_ADMIN', $admin->getRoles(), true)) {
            return;
        } elseif ($user && $user->getSlug() !== $slug) {
            $response = new RedirectResponse(
                $this->urlGenerator->generate(
                    'app_accueil',
                    [
                    'slug' => $user->getSlug()
                    ]
                )
            );
            $event->setResponse($response);
            return;
        }
    }
}
