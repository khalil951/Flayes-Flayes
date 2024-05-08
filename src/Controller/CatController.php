<?php

namespace App\Controller;

use App\Form\CatFormType;
use App\Repository\CatRepository;
use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class CatController extends AbstractController
{
    #[Route('/cat', name: 'app_cat')]
    public function index(): Response
    {
        return $this->render('cat/cat.html.twig', [
            'controller_name' => 'CatController',
        ]);
    }

    #[Route('/categoryadd', name: 'app_category_add')]
public function add(ManagerRegistry $doctrine, Request $request): Response
{  
    $category = new Category();
    $form = $this->createForm(CatformType::class, $category);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->flush();
        
        return $this->redirectToRoute('cat_show');
    }
    
    return $this->render('cat/cat.html.twig', [
        'f' => $form->createView(),
        'category'=> $category,
    ]);
}


    #[Route('/listcat', name: 'cat_show')]
    public function list(CatRepository $rep): Response
    {
        $categories = $rep->findAll();
        
        return $this->render('cat/showcatback.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_deletecat')]
    public function delete(ManagerRegistry $doctrine, $id, CatRepository $rep): Response
    {  
        $category = $rep->find($id);
        
        // Check if a category with the given ID exists
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
    
        // Remove the category
        $em = $doctrine->getManager();
        $em->remove($category);
        $em->flush();
    
        // Redirect to the category list page after deletion
        return $this->redirectToRoute('cat_show');
    }  

    #[Route('/categoryUpdate/{id}', name: 'app_categoryUpdate')]
    public function update($id, ManagerRegistry $doctrine, Request $req, CatRepository $rep): Response
    {    
        // Find the category entity by its ID
        $category = $rep->find($id);
        
        // Check if the category exists
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
    
        // Create the form using the appropriate form type and pass the category entity
        $form = $this->createForm(CatformType::class, $category);
        $form->handleRequest($req);
       
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // If so, persist the changes to the category entity
            $em = $doctrine->getManager();
            $em->flush();
            
            // Redirect to the category list page after updating
            return $this->redirectToRoute('cat_show');
        }
    
        // Render the update form template with the form view
        return $this->render('cat/catupdate.html.twig', [
            'f' => $form->createView()
        ]);
    }
}
