<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventAddFormType; // Updated import for EventAddFormType
use App\Form\EditEventFormType; // Updated import for EventAddFormType

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile class
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
public function list(Request $request, EventRepository $eventRepository): Response
{
    $events = $eventRepository->findAll();

    $entity = new Event();
    $form = $this->createForm(EventAddFormType::class, $entity);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission for adding a new event
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            try {
                $imageDirectory = $this->getParameter('kernel.project_dir').'/src/image/';
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($imageDirectory, $newFilename);
                $entity->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'An error occurred while uploading the image.');
                return $this->redirectToRoute('event_show');
            }
        }
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->redirectToRoute('event_show');
    }
    
    // Render the template with both the events list and the form
    return $this->render('event/showeventback.html.twig', [
        'events' => $events,
        'form' => $form->createView()
    ]);
}

#[Route('/authorAdd', name: 'app_authorAdd')]
public function adde(Request $req, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
{  
    $entity = new Event(); // Assuming your entity is named Event
    $form = $this->createForm(EventAddFormType::class, $entity);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Convert DateTime object to string before persisting
        $date = $entity->getDate(); // Assuming 'date' is the property name

        // Get the EntityManager from the injected dependency
        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->redirectToRoute('app_authorAdd');
    }

    return $this->render('event/showeventback.html.twig', [
        'form' => $form->createView()
    ]);
}

  public function addEvent(Request $request)
    {
        // Create a new instance of the Event entity
        $event = new Event();

        // Create the form and bind the entity to it
        $form = $this->createForm(EventAddFormType::class, $event);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Convert the date to string before setting it
            $event->setDate($event->getDate()->format('Y-m-d H:i:s'));

            // Persist the entity to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            // Redirect to a success page or return a response
            // For example:
            return $this->redirectToRoute('success_route');
        }

        // Render the form in the template
        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
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
        $form = $this->createForm(EventAddFormType::class, $entity);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload for the image field
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                try {
                    // Define the image directory
                    $imageDirectory = $this->getParameter('kernel.project_dir').'/src/image/';
                    
                    // Generate a unique filename for the uploaded file
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    
                    // Move the uploaded file to the desired directory
                    $imageFile->move($imageDirectory, $newFilename);
                    
                    // Set the image filename in the entity
                    $entity->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error, if any
                    // For example, log the error or display a flash message
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_EventAdd');
                }
            }
            
            // Persist the entity to the database
            $entityManager->persist($entity);
            $entityManager->flush();
            
            return $this->redirectToRoute('event_show');
        }
        
        return $this->render('event/add.html.twig', [
            'f' => $form->createView()
        ]);
    }



// Inside your EventController class
#[Route('/EventUpdate/{id}', name: 'event_Update')]#[Route('/EventUpdate/{id}', name: 'event_Update')]
public function update($id, Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository): Response
{    
    // Retrieve the image directory from the container
    $imageDirectory = $this->getParameter('kernel.project_dir').'/src/image/';

    $event = $eventRepository->find($id);
    
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    // Store the current image filename
    $currentImage = $event->getImage();

    $form = $this->createForm(EditEventFormType::class, $event);
    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();

        // Check if a new image was uploaded
        if ($imageFile instanceof UploadedFile) {
            // Handle file upload
            try {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $imageDirectory,
                    $newFilename
                );
                $event->setImage($newFilename);
            } catch (FileException $e) {
                // Handle file upload error, if any
                // For example, log the error or display a flash message
                $this->addFlash('error', 'An error occurred while uploading the image.');
                return $this->redirectToRoute('event_Update', ['id' => $id]);
            }
        } else {
            // If no new image was uploaded, retain the existing image filename
            $event->setImage($currentImage);
        }

        $entityManager->flush();
        return $this->redirectToRoute('event_show');
    }

    return $this->render('event/updateeventback.html.twig', [
        'f' => $form->createView()
    ]);
}

}
