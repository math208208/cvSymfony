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
use App\Service\TranslationService;
use Symfony\Component\HttpFoundation\Request;

final class ExperienceController extends AbstractController
{
    #[Route('/{slug}/experiences', name: 'app_experience')]
    public function show(
        string $slug, 
        UserRepository $userRepository, 
        ExperienceProRepository $repoExpPro,
        ExperienceUniRepository $repoExpUni,
        TranslationService $translator,
        Request $request
    ): Response
    {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);

        $user = $userRepository->findOneBySlug($slug);
        
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $experiencesPro = $repoExpPro->findByUser($user);  
        foreach ($experiencesPro as $key => $experiencePro) {
            if ($experiencePro->isArchived()) {
                unset($experiencesPro[$key]); 
            }
        }
        $experiencesUni = $repoExpUni->findByUser($user);  
        foreach ($experiencesUni as $key => $experienceUni) {
            if ($experienceUni->isArchived()) {
                unset($experiencesUni[$key]); 
            }
        }

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
                $experienceUni->setTitre($translation->getTitre());
                $experienceUni->setSousTitre($translation->getSousTitre());
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
        
