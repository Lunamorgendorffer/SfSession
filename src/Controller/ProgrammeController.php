<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Form\ProgrammeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(): Response
    {
        return $this->render('programme/index.html.twig', [
            'controller_name' => 'ProgrammeController',
        ]);
    }

    // fonction ajout + edit une programme
    #[Route('/programme/add/{sessionId}', name: 'add_programme')]
    #[Route('/programme/{id}/edit', name: 'edit_programme')]
    public function add(EntityManagerInterface $entityManager, Request $request, Programme $programme = null, $sessionId = null): Response 
    {
        if (!$programme){ // si la programme n'existe pas 
            $programme = new Programme();  // alors crée un nouvel objet programme 
        }
        $session= $entityManager->getRepository(Session::class)->find($sessionId);
        
        // on crée le formulaire 
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $programme = $form->getData();
            $entityManager->persist($programme);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute ('show_session', ['id' => $sessionId]);

        }

        // vue pour afficher le formulaire 
        return $this->render('programme/addprogramme.html.twig', [
           'formAddprogramme' => $form->createView(),
           'edit'=> $programme->getId()
            
        ]);
    }
}
