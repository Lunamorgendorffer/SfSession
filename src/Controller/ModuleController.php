<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\ModuleType;
use App\Entity\Categorie;
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
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            
           
        ]);
    }

    #[Route('/module/add/{categorieID}', name: 'add_module')]
    #[Route('/module/{id}/edit/{categorieID}', name: 'edit_module')]
    public function add(EntityManagerInterface $em, ModuleSession $module = null, Request $request, $categorieID = null): Response
    {
        if(!$module){

            $module = new ModuleSession();
        }

        $categorie= $em->getRepository(Categorie::class)->find($categorieID);

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formulaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $module->setCategorie($categorie);

            $module = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form

            $em->persist($module); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('show_categorie', ['id'=>$categorieID]);
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('module/addModule.html.twig', [
            'formAddmodule' => $form->createView(),
            'edit' => $module->getId()]); // création du formulaire
    }
    

    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(EntityManagerInterface $entityManager, ModuleSession $module): Response
    {
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_categorie');

    }

    #[Route('/module/{id}', name: 'show_module')]
    public function show(ModuleSession $module): Response
    {
        return $this->render('module/detailModule.html.twig', [
           'module' => $module,
        ]);
    }




}
