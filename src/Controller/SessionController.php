<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{


    #[Route('/session', name: 'app_session')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sessions = $entityManager->getRepository(Session::class)->findAll();
        $formations = $entityManager->getRepository(Formation::class)->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'formations' => $formations
        ]);
    }

    #[Route('/session/add', name: 'add_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function add(EntityManagerInterface $entityManager, Session $session = null, Request $request): Response 
    {
        if (!$session){ // si la session n'existe pas 
            $session = new Session();  // alors crée un nouvel objet session 
        }
        // on crée le formulaire 
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $session = $form->getData();
            $entityManager->persist($session);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_session');

        }

        // vue pour afficher le formulaire 
        return $this->render('session/addSession.html.twig', [
           'formAddsession' => $form->createView(),
           'edit'=> $session->getId()
            
        ]);

    }

    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(EntityManagerInterface $entityManager, Session $session): Response
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');

    }

    
    #[Route('/session/{id}', name: 'show_session')]

    // #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Session $session): Response
    {
        return $this->render('session/detailSession.html.twig', [
           'session' => $session,
           'formation' => $session->getFormations(),
           'programme' => $session->getProgrammes(),
        ]);
    }
    
    // #[Route('/formation/{id}', name: 'show_formation')]
    // public function showFormation(Formation $formation): Response
    // {
    //     return $this->render('formation/detailFormation.html.twig', [
    //        'formation' => $formation,
    //        'sessions' => $formation->getSessions(),
    //     ]);
    // }
    

      
}
