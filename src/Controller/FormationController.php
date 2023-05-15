<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager->getRepository(Formation::class)->findAll();
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/formation/add', name: 'add_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function add(EntityManagerInterface $entityManager, Formation $formation = null, Request $request): Response 
    {
        if (!$formation){ // si la formation n'existe pas 
            $formation = new Formation();  // alors crée un nouvel objet formation 
        }
        // on crée le formulaire 
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $formation = $form->getData();
            $entityManager->persist($formation);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_formation');

        }

        // vue pour afficher le formulaire 
        return $this->render('formation/addFormation.html.twig', [
           'formAddformation' => $form->createView(),
           'edit'=> $formation->getId()
            
        ]);

    }

    #[Route('/formation/{id}/delete', name: 'delete_formation')]
    public function delete(EntityManagerInterface $entityManager, Formation $formation): Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('app_formation');

    }

    
    #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/detailFormation.html.twig', [
           'formation' => $formation,
        ]);
    }
}
