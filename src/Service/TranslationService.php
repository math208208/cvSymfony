<?php
namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;


class TranslationService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function translate(
        object $entity,
        string $locale,
        string $translationClass
    ): ?object {
        return $this->em->getRepository($translationClass)
            ->findOneBy([
                'translatable' => $entity,
                'locale' => $locale
            ]);
    }
}