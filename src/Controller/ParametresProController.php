<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\Langage;
use App\Entity\Loisir;
use App\Entity\Outil;
use App\Entity\User;
use App\Form\CompetencesType;
use App\Form\LangueType;
use App\Form\LoisirType;
use App\Form\OutilType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ParametresProController extends AbstractController
{
    #[Route('/parametres', name: 'app_parametresPro')]
    public function show(
    ): Response {

       







        return $this->render(
            'parametres/pro/index.html.twig',
        );
    }

}