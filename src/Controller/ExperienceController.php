<?php
namespace App\Controller;

use App\Entity\ExperiencePro;
use App\Entity\ExperienceUni;
use App\Entity\Translation\ExperienceProTranslation;
use App\Entity\Translation\ExperienceUniTranslation;
use App\Entity\User;
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
        string $_locale,
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



        $translatedProfession = $translator->translate(User::class, $user->getId(), 'profession', $user->getProfession() ?? 'Aucune donnée', $_locale);
        $translatedDescription = $translator->translate(User::class, $user->getId(), 'description', $user->getDescription() ?? 'Aucune donnée', $_locale);

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];
        

        $translatedExperiencesPro = [];
    
        foreach ($experiencesPro as $experiencePro) {
            $translatedPoste = $translator->translate(ExperiencePro::class, $experiencePro->getId(), 'poste', $experiencePro->getPoste(),$_locale);
            $translatedDescription = $translator->translate(ExperiencePro::class, $experiencePro->getId(), 'description', $experiencePro->getDescription(),$_locale);

            
            $translatedExperiencesPro[] = [
                'experiencePro' => $experiencePro,
                'translated_poste' => $translatedPoste,
                'translated_description' => $translatedDescription
            ];
        }

        $translatedExperiencesUni = [];
    
        foreach ($experiencesUni as $experienceUni) {
            $translatedTitre = $translator->translate(ExperienceUni::class, $experienceUni->getId(), 'titre', $experienceUni->getTitre(),$_locale);
            $translatedSousTitre = $translator->translate(ExperienceUni::class, $experienceUni->getId(), 'sousTitre', $experienceUni->getSousTitre(),$_locale);
            $translatedDescription = $translator->translate(ExperienceUni::class, $experienceUni->getId(), 'description', $experienceUni->getDescription(),$_locale);

            
            $translatedExperiencesUni[] = [
                'experienceUni' => $experienceUni,
                'translated_titre' => $translatedTitre,
                'translated_sousTitre' => $translatedSousTitre,
                'translated_description' => $translatedDescription
            ];
        }

       


        return $this->render('experiences/index.html.twig', [
            'user' => $translatedUser,
            'experiencesPro' => $translatedExperiencesPro,  
            'experiencesUni' => $translatedExperiencesUni,  
        ]);
    }
}
        
