<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Translation\ExperienceProTranslationCrudController;
use App\Controller\Admin\Translation\ExperienceUniTranslationCrudController;
use App\Controller\Admin\Translation\FormationTransalationCrudController;
use App\Controller\Admin\Translation\LangageTransalationCrudController;
use App\Controller\Admin\Translation\LoisirTransalationCrudController;
use App\Controller\Admin\Translation\UserTransalationCrudController;
use App\Entity\Translation\ExperienceProTranslation as TranslationExperienceProTranslation;
use App\Entity\Translation\ExperienceUniTranslation;
use App\Entity\Translation\FormationTranslation;
use App\Entity\Translation\LangageTranslation;
use App\Entity\Translation\LoisirTranslation;
use App\Entity\Translation\UserTranslation;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


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
        yield MenuItem::linkToCrud('Trad Personne', 'fas fa-language', UserTranslation::class)
        ->setController(UserTransalationCrudController::class);

        yield MenuItem::linkToCrud('Trad Formation', 'fas fa-language', FormationTranslation::class)
        ->setController(FormationTransalationCrudController::class);
        yield MenuItem::linkToCrud('Trad Loisir', 'fas fa-language', LoisirTranslation::class)
        ->setController(LoisirTransalationCrudController::class);

        yield MenuItem::linkToCrud('Trad Expérience Uni', 'fas fa-language', ExperienceUniTranslation::class)
        ->setController(ExperienceUniTranslationCrudController::class);
        yield MenuItem::linkToCrud('Trad Expérience Pro', 'fas fa-language', TranslationExperienceProTranslation::class)
        ->setController(ExperienceProTranslationCrudController::class);

        yield MenuItem::linkToCrud('Trad Langues', 'fas fa-language', LangageTranslation::class)
        ->setController(LangageTransalationCrudController::class);
        
        



    }
}
