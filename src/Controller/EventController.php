<?php

namespace App\Controller;

use App\Entity\Event;
use Twilio\Rest\Client;

use App\Form\EventAddFormType;
use App\Controller\FileException;
use App\Form\EditEventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/deleteevent/{id}', name: 'app_deleteevent')]
    public function delete(ManagerRegistry $doctrine, $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);
        if (!$event) {
            $this->addFlash('error', 'No event found for id ' . $id);
            return $this->redirectToRoute('show_myeventsback');
        }
    
        $entityManager = $doctrine->getManager();
        $entityManager->remove($event);
        $entityManager->flush();
        $this->addFlash('success', 'Event successfully deleted.');
    
        return $this->redirectToRoute('show_myeventsback');
    }
    #[Route('/Addevent', name: 'app_event_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response {
        $event = new Event();
        $form = $this->createForm(EventAddFormType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $event->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                    return $this->redirectToRoute('show_myeventsback');
                }
            }
    
            // Prepare data for QR code generation
            $qrData = json_encode([
                'name' => $event->getName(),
                'date' => $event->getDate(),
                'description' => $event->getDescription(),
            ]);
    
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);
            $qrCode->setForegroundColor(new Color(0, 0, 0));
            $qrCode->setBackgroundColor(new Color(255, 255, 255));
    
            $filesystem = new Filesystem();
            $qrCodeDirectory = $this->getParameter('kernel.project_dir') . '/public/qr';
            $qrFileName = uniqid() . '_qr.png';
            $qrCodePath = $qrCodeDirectory . '/' . $qrFileName;
    
            try {
                if (!$filesystem->exists($qrCodeDirectory)) {
                    $filesystem->mkdir($qrCodeDirectory, 0777);
                }
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                $result->saveToFile($qrCodePath);
                $event->setQrCode($qrFileName);
            } catch (\Exception $e) {
                $event->setQrCode('default_qr.png');
                $this->addFlash('warning', 'QR code generation failed, default QR code used.');
            }
            
    
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Event successfully added.');
    
            return $this->redirectToRoute('show_myeventsback');
        }
    
        return $this->render('event/create_event.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    

    #[Route('/authorAdd', name: 'app_authorAdd')]
    public function adde(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new Event();
        $form = $this->createForm(EventAddFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $file */
            $file = $form->get('image')->getData();
            if ($file instanceof UploadedFile) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^A-Za-z0-9_\-]/', '', strtolower($originalFilename));
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move($this->getParameter('images_directory'), $newFilename);
                    $entity->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                    return $this->redirectToRoute('show_myevents');
                }
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('success', 'Event added successfully.');

            return $this->redirectToRoute('show_myevents');
        }

        return $this->render('event/create_event.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/event/update/{id}', name: 'event_Update')]  // Ensure route name is all lowercase
    public function update($id, Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);
        if (!$event) {
            $this->addFlash('error', 'Event not found.');
            return $this->redirectToRoute('show_myevents');  // Ensure this route is correctly defined in your routes
        }
    
        $form = $this->createForm(EditEventFormType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile instanceof UploadedFile) {
                $newFilename = uniqid('', true) . '.' . $imageFile->guessExtension();  // Added more randomness to the filename
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $event->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to update image: ' . $e->getMessage());
                    return $this->redirectToRoute('show_myevents');
                }
            }
    
            $entityManager->persist($event);  // Explicitly persisting might be redundant but clarifies intent
            $entityManager->flush();
            $this->addFlash('success', 'Event updated successfully.');
    
            return $this->redirectToRoute('show_myeventsback');  // Ensure this route is correctly defined in your routes
        }
    
        return $this->render('event/updateevent.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    
    #[Route('/show_myevents', name: 'show_myevents')]
    public function list(EventRepository $repository): Response
    {
        $events = $repository->findAll();
        $imagesDirectory = $this->getParameter('images_directory');

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'images_directory' => $imagesDirectory,
        ]);
    }

   
    // src/Controller/EventController.php

// src/Controller/EventController.php
 #[Route('/show_myeventsback', name: 'show_myeventsback')]
    public function listBack(EventRepository $repository): Response
    {
        $imagesDirectory = $this->getParameter('images_directory');
        $events = $repository->findAll();

        return $this->render('event/showeventback.html.twig', [
            'events' => $events,
            'images_directory' => $imagesDirectory
        ]);
    }
#[Route('/searchevent', name: 'app_searchevent', methods: ['GET'])]
public function searchEvent(Request $request, EventRepository $eventRepository): Response
{
    $query = $request->query->get('query', '');
    $events = $eventRepository->searchAll($query);

    if ($request->isXmlHttpRequest()) {
        // Assuming 'event/_list.html.twig' only contains the part of the page that lists events
        return $this->render('event/_list.html.twig', [
            'events' => $events,
        ]);
    }

    return $this->redirectToRoute('show_myeventsback');
}

#[Route('/sortdescevent', name: 'app_sortdescevent')]
public function sortdesc(EventRepository $rep): Response
{    
    $events = $rep->descevent();  // Fetches events sorted by name in descending order
    $imagesDirectory = $this->getParameter('images_directory');

    return $this->render('event/showeventback.html.twig', [
        'events' => $events,
        'images_directory' => $imagesDirectory

    ]);
}


#[Route('/show_myeventsback', name: 'show_myeventsback')]
public function listBackk(EventRepository $repository): Response
{
    $imagesDirectory = $this->getParameter('images_directory');
    $events = $repository->findAll();

    return $this->render('event/showeventback.html.twig', [
        'events' => $events,
        'images_directory' => $imagesDirectory
    ]);
}

/*
#[Route('/show_myeventsback', name: 'show_myeventsback')]
public function listBackk(Request $request, EventRepository $repository): Response
{
    $imagesDirectory = $this->getParameter('images_directory');
    $query = $request->query->get('query');

    if ($query) {
        $events = $repository->searchAll($query);
    } else {
        $events = $repository->findAll();
    }

    if ($request->isXmlHttpRequest()) {
        // Render only the partial list for AJAX requests
        return $this->render('event/_list.html.twig', [
            'events' => $events,
            'images_directory' => $imagesDirectory
        ]);
    }

    // Render the full page for normal requests
    return $this->render('event/showeventback.html.twig', [
        'events' => $events,
        'images_directory' => $imagesDirectory
    ]);
}

 */
}
