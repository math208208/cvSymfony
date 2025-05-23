<?php

namespace App\Service;

use App\Repository\TranslationRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationService
{
    private string $locale;

    public function __construct(
        private TranslationRepository $repo,
        RequestStack $requestStack
    ) {
        $this->locale = $requestStack->getCurrentRequest()?->getLocale() ?? 'fr';
    }




    public function translate(
        string $entityName,     //Nom de l'entité auquel on veut traduire un attribut
        int $entityId,          //Id de l'entité
        string $attribute,      //Attribut de l'entité à traduire
        string $default,        //Valeur par défaut si la traduction n'existe pas 
        ?string $locale = null  //Locale de la traduction (la langue)
    ): string {
        $locale = $locale ?? $this->locale;

        //On cherche la traduction dans la base de données a partir 
        //de l'entité, de l'id de l'entité et de l'attribut
        $translation = $this->repo->findOneBy(
            [
            'entity' => $entityName,
            'entityId' => $entityId,
            'attribute' => $attribute,
            ]
        );

        //Si la traduction n'existe pas, on retourne la valeur par défaut
        if (!$translation) {
            return $default;
        }

        //On cherche la méthode de la traduction correspondant à la locale
        $getter = 'get' . ucfirst($locale);

        //On vérifie si la méthode existe et on l'appelle
        if (method_exists($translation, $getter)) {
            return $translation->$getter();
        }

        //Si la méthode n'existe pas, on retourne la valeur par défaut
        return $default;
    }
}



