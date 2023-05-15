<?php

namespace App\Controller;

use App\Form\ModuleType;
use App\Entity\ModuleSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $modules = $entityManager->getRepository(ModuleSession::class)->findBy([], ["intitule" => "ASC"]);
        $programmes = $entityManager->getRepository(Programme::class)->findAll();
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            $programmes => $programmes,
        ]);
    }

    #[Route('/module/add', name: 'add_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function add(EntityManagerInterface $entityManager, ModuleSession $module = null, Request $request): Response 
    {
        if (!$module){ // si la module n'existe pas 
            $module = new ModuleSession();  // alors crée un nouvel objet module 
        }
        // on crée le formulaire 
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $module = $form->getData();
            $entityManager->persist($module);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_module');

        }

        // vue pour afficher le formulaire 
        return $this->render('module/addModule.html.twig', [
           'formAddmodule' => $form->createView(),
           'edit'=> $module->getId()
            
        ]);

    }

    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(EntityManagerInterface $entityManager, module $module): Response
    {
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_module');

    }

    #[Route('/module/{id}', name: 'show_module')]
    public function show(ModuleSession $module): Response
    {
        return $this->render('module/detailModule.html.twig', [
           'module' => $module,
        ]);
    }




}
