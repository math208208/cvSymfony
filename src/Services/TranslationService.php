<?php

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationService
{
    public function __construct(
        private TranslatorInterface $translator,
        private EntityManagerInterface $em
    ) {}

    public function translateEntity($entity, string $locale): void
    {
        if (method_exists($entity, 'getDescription')) {
            $translated = $this->translator->trans($entity->getDescription());
            $entity->setDescription($translated);
        }
    }
}