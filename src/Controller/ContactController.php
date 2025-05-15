<?php

namespace App\Controller;

use ApiPlatform\OpenApi\Model\Contact;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\MessageRepository;
use App\Repository\ProfessionnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class ContactController extends AbstractController
{
    #[Route('/{slug}/contact', name: 'app_contact')]
    public function show(
        string $slug,
        UserRepository $userRepository,
        ProfessionnelRepository $professionnelRepository,
        MessageRepository $messageRepository,
        TranslationService $translator,
        string $_locale,
        Security $security,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('VIEW_PROFILE', $slug);


        $user = $userRepository->findOneBySlug($slug);
        if (!$user) {
            throw $this->createNotFoundException('CV non trouvé.');
        }


        $translatedProfession = $translator->translate(
            User::class,
            $user->getId(),
            'profession',
            $user->getProfession() ?? 'Aucune donnée',
            $_locale
        );
        $translatedDescription = $translator->translate(
            User::class,
            $user->getId(),
            'description',
            $user->getDescription() ?? 'Aucune donnée',
            $_locale
        );

        $translatedUser = [
            'user' => $user,
            'translated_profession' => $translatedProfession,
            'translated_description' => $translatedDescription
        ];


    

        if($this->isGranted('ROLE_PRO')){
            $layout= 'base/pro/index.html.twig';
            $view= 'contact/message/form.html.twig';

            $formContact = $this->createForm(ContactType::class);
            $formContact->handleRequest($request);
    
            if ($formContact->isSubmitted() && $formContact->isValid()) {
                $message = new Message();
                $messageForm = $formContact->get('message')->getData();
                $admin = $security->getUser();
                /** @var \App\Entity\Admin $admin */
                $email=$admin->getEmail();
                $expediteur = $professionnelRepository->findOneBy(['email' => $email]);
                $receveur = $userRepository->findOneBy(['slug' => $slug]);
    
                $message->setMessage($messageForm);
                $message->setExpediteur($expediteur);
                $message->setReceveur($receveur);
    
                $em->persist($message);
                $em->flush();
                return $this->redirectToRoute('app_contact', ['slug' => $user->getSlug()]);
            }
    
            $formView = $formContact->createView();

            return $this->render(
                'contact/index.html.twig',
                [
                    'layout' => $layout,
                    'user' => $translatedUser,
                    'view' => $view,
                    'formContact' => $formView
                ]
            );

        }else{
            $admin = $security->getUser();
            /** @var \App\Entity\Admin $admin */
            $email = $admin->getEmail();
            $userCo = $userRepository->findOneBy(['email' => $email]);
            $view='contact/message/message.html.twig';
            if($slug===$userCo->getSlug()){
                $layout = 'base/user/index.html.twig';
                $messages = $messageRepository->findByUser($user);
                return $this->render(
                    'contact/index.html.twig',
                    [
                        'layout' => $layout,
                        'user' => $translatedUser,
                        'view' => $view,
                        'messages' => $messages,
                        'userCo'=>$userCo,
    
                    ]
                );
            }else{
                $layout = 'base/user/explo.html.twig';
                return $this->render(
                    'contact/index.html.twig',
                    [
                        'layout' => $layout,
                        'user' => $translatedUser,
                        'view' => $view,
                        'userCo'=>$userCo,
    
                    ]
                );
            }


            


            

            


        }


        
    }
}
