<?php

namespace App\Controller\Admin;

use App\Entity\ExperienceProTranslation;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $url = $this->generateUrl('admin_user_index'); 
        return new RedirectResponse($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Générateur de CV')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Mon CV');
        yield MenuItem::linkToCrud('Personne', 'fa fa-user', \App\Entity\User::class);

        yield MenuItem::section('Accueil');
        yield MenuItem::linkToCrud('Formation', 'fa fa-graduation-cap', \App\Entity\Formation::class);
        yield MenuItem::linkToCrud('Loisirs', 'fa fa-heart', \App\Entity\Loisir::class);

        yield MenuItem::section('Experiences');
        yield MenuItem::linkToCrud('ExperienceUni', 'fa fa-university', \App\Entity\ExperienceUni::class);
        yield MenuItem::linkToCrud('ExperiencePro', 'fa fa-briefcase', \App\Entity\ExperiencePro::class);

        yield MenuItem::section('Competences');
        yield MenuItem::linkToCrud('Competences', 'fa fa-cogs', \App\Entity\Competence::class);
        yield MenuItem::linkToCrud('Outils', 'fa fa-wrench', \App\Entity\Outil::class);
        yield MenuItem::linkToCrud('Langues', 'fa fa-language', \App\Entity\Langage::class);

        yield MenuItem::section('Traductions');
        yield MenuItem::linkToCrud('Trad Expérience Pro', 'fas fa-language', ExperienceProTranslation::class);



    }
}
