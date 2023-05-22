<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Entity\ModuleSession;
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

    // fonction ajout + edit une session
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

    // fonction delete d'une session 
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(EntityManagerInterface $entityManager, Session $session): Response
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');

    }

    // fonction ajouter un stagiaire à une session
    #[Route('/session/{id}/addStagiaire/{idStagiaire}', name: 'addStagiaire')]
    public function addStagiaireToSession(EntityManagerInterface $entityManager, Session $session, int $idStagiaire): Response 
    {
        // Trouver le stagiaire correspondant à l'idStagiaire
        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($idStagiaire);
        
        if ($stagiaire) {
            // Ajouter le stagiaire à la session
            $session->addStagiaire($stagiaire);
            $entityManager->flush();
            
            // Afficher un message de succès
            $this->addFlash('success', 'Stagiaire ajouté à la session avec succès.');
        } else {
            // Afficher un message d'erreur si le stagiaire n'est pas trouvé
            $this->addFlash('error', 'Stagiaire introuvable.');
        }
        
        // Rediriger vers la page d'affichage de la session
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
    
    // fonction pour retirer un stagaire de la session
    #[Route('/session/{id}/removeStagiaire/{idStagiaire}', name: 'removeStagiaire')]
    public function removeStagiaireToSession(EntityManagerInterface $entityManager, Session $session, int $id, int $idStagiaire): Response
    {
        // Trouver le stagiaire correspondant à l'idStagiaire
        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($idStagiaire); 

        // Supprimer le stagiaire de la session
        $session->removeStagiaire($stagiaire);
        
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();
        
        // Rediriger vers la page d'affichage de la session
        return $this->redirectToRoute('show_session', ['id' => $id]);
    }

  
    // public function addProgrammeToSession(EntityManagerInterface $entityManager, Session $session, int $programmeId): Response
    // {
    //    $module = $entityManager->getRepository(Programme::class)->find($programmeId);
    //    if($programme){
    //        $session->addProgramme($programme);
    //        $entityManager->flush();
    //    }

    //    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);

    // }


    // fonction pour afficher la page detail de la session 
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response
    {
        // Récupérer les stagiaires qui ne sont pas dans la session
        $stagiaires = $sessionRepository->findStagiairesNotInSession($session->getId());
        // $session = $entityManager->getRepository(Session::class)->findAll();

        // Retourne sur la vue 'session/detailSession.html.twig' avec les données suivantes
        return $this->render('session/detailSession.html.twig', [
        'session' => $session,             // La session à afficher
        'programme' => $session->getProgrammes(),     // Les programmes de la session
        'stagiaires' => $stagiaires        // Les stagiaires qui ne sont pas dans la session
        ]);
    }

    
  
    

      
}
