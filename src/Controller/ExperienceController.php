<?php
namespace App\Controller;

use App\Entity\ExperienceProTranslation as EntityExperienceProTranslation;
use App\Repository\ExperienceProRepository;
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
        ExperienceProRepository $repo,
        ServiceTranslationService $translator,
        Request $request
    ): Response
    {
        $user = $userRepository->findOneBySlug($slug);
        
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $experiencesPro = $repo->findByUser($user);  

        $locale = $request->getLocale();

        //permet de recup uniquement les données du user en question 
        foreach ($experiencesPro as $experience) {
            if ($translation = $translator->translate($experience, $locale, EntityExperienceProTranslation::class)) {
                $experience->setPoste($translation->getPoste());
                $experience->setDescription($translation->getDescription());
            }
        }

        return $this->render('experiences/index.html.twig', [
            'user' => $user,
            'experiencesPro' => $experiencesPro,  
            'experiencesUni' => $user->getExperiencesUni(),  
        ]);
    }
}
        
