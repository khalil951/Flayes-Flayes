<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventAddFormType; // Updated import for EventAddFormType
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    
    #[Route('/list', name: 'event_show')]
    public function list(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
    
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $entityManager, $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);
        
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
        
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('event_show');
    }
    
    #[Route('/EventAdd', name: 'app_EventAdd')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {  
        $entity = new Event();
        $form = $this->createForm(EventAddFormType::class, $entity); // Updated form class
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            
            return $this->redirectToRoute('event_show');
        }
        
        return $this->render('event/add.html.twig', [
            'f' => $form->createView()
        ]);
    }

    #[Route('/EventUpdate/{{id}}', name: 'event_Update')]
public function update($id,ManagerRegistry $doctrine, Request  $req,EventRepository $rep): Response
{    
    {  $event=$rep->find($id);
        $form=$this->createForm(EventAddFormType::class,$event);
    $form->handleRequest($req);
   
  if($form->isSubmitted()){
   $em=$doctrine->getManager();
   $em->persist($event);
   $em->flush();
    /* return $this->render('author/list.html.twig', [
    'controller_name' => 'AuthorController',
]); */
return $this->redirectToRoute('event_show');}
return $this->render('event/update.html.twig',[
    'f'=>$form->createView()
]);
  }
}
}
