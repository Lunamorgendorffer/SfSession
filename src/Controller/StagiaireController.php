<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(EntityManagerInterface  $entityManager): Response
    {
        $stagiaires = $entityManager->getRepository(Stagiaire::class)->findAll();
        return $this->render('stagiaire/index.html.twig', [
                'stagiaires' => $stagiaires,
            ]);
    }

    #[Route('/stagiaire/add', name: 'add_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function add(EntityManagerInterface $entityManager, Stagiaire $stagiaire = null, Request $request): Response 
    {
        if (!$stagiaire){
            $stagiaire = new stagiaire();
        }
        // on crée le formulaire 
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $stagiaire = $form->getData();
            $entityManager->persist($stagiaire);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_stagiaire');

        }

        // vue pour afficher le formulaire 
        return $this->render('stagiaire/addStagiaire.html.twig', [
           'formAddstagiaire' => $form->createView(),
           'edit'=> $stagiaire->getId()
            
        ]);

    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(EntityManagerInterface $entityManager, Stagiaire $stagiaire): Response
    {
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');

    }

    
    
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
}
