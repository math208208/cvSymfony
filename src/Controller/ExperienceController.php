<?php
namespace App\Controller;

use App\Entity\Translation\ExperienceProTranslation;
use App\Entity\Translation\ExperienceUniTranslation;
use App\Repository\ExperienceProRepository;
use App\Repository\ExperienceUniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService as ServiceTranslationService;
use Symfony\Component\HttpFoundation\Request;

final class ExperienceController extends AbstractController
{
    #[Route('/{slug}/experiences', name: 'app_experience')]
    public function show(
        string $slug, 
        UserRepository $userRepository, 
        ExperienceProRepository $repoExpPro,
        ExperienceUniRepository $repoExpUni,

        ServiceTranslationService $translator,
        Request $request
    ): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $experiencesPro = $repoExpPro->findByUser($user);  
        $experiencesUni = $repoExpUni->findByUser($user);  

        $locale = $request->getLocale();

        //permet de recup uniquement les données du user en question 
        foreach ($experiencesPro as $experiencePro) {
            if ($translation = $translator->translate($experiencePro, $locale, ExperienceProTranslation::class)) {
                $experiencePro->setPoste($translation->getPoste());
                $experiencePro->setDescription($translation->getDescription());
            }
        }

        foreach ($experiencesUni as $experienceUni) {
            if ($translation = $translator->translate($experienceUni, $locale, ExperienceUniTranslation::class)) {
                $experienceUni->setPoste($translation->getPoste());
                $experienceUni->setDescription($translation->getDescription());
            }
        }

        return $this->render('experiences/index.html.twig', [
            'user' => $user,
            'experiencesPro' => $experiencesPro,  
            'experiencesUni' => $experiencesUni,  
        ]);
    }
}
        
