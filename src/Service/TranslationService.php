<?php
namespace App\Service;

use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationService
{
    private string $locale;

    public function __construct(
        private TranslationRepository $repo,
        private RequestStack $requestStack
    ) {
        $this->locale = $requestStack->getCurrentRequest()?->getLocale() ?? 'fr';
    }

    public function translate(string $entityName, int $entityId, string $attribute,string $default,  string $locale): string
    {
        $translation = $this->repo->findOneBy([
            'entity' => $entityName,
            'entityId' => $entityId,
            'attribute' => $attribute,
        ]);

        if (!$translation) {
            return $default;
        }

        $getter = 'get' . ucfirst($locale);

        if (method_exists($translation, $getter)) {
            return $translation->$getter() ;
        }
    
        return $default;
    }
}