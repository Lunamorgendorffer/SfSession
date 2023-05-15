<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findBy([], ["intitule" => "ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/add', name: 'add_categorie')]
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    public function add(EntityManagerInterface $entityManager, Categorie $categorie = null, Request $request): Response 
    {
        if (!$categorie){ // si la categorie n'existe pas 
            $categorie = new Categorie();  // alors crée un nouvel objet categorie 
        }
        // on crée le formulaire 
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $categorie = $form->getData();
            $entityManager->persist($categorie);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_categorie');

        }

        // vue pour afficher le formulaire 
        return $this->render('categorie/addcategorie.html.twig', [
           'formAddcategorie' => $form->createView(),
           'edit'=> $categorie->getId()
            
        ]);

    }

    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function delete(EntityManagerInterface $entityManager, Categorie $categorie): Response
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('app_categorie');

    }

    #[Route('/categorie/{id}', name: 'show_categorie')]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/detailCategorie.html.twig', [
           'categorie' => $categorie,
           'module' => $categorie->getModuleSessions()
        ]);
    }




}
