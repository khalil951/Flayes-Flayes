<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;

use App\Form\EventAddFormType;
use App\Form\TicketFormType;
use App\Entity\Ticket;
use App\Entity\Event;
use Dompdf\Options;  // Correct use statement for Dompdf Options
use Dompdf\Dompdf;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Picqer\Barcode\BarcodeGeneratorPNG;  // Correct location for the use statement

class TicketController extends AbstractController
{  
    #[Route('/ticket', name: 'app_ticket')]
    public function index(): Response
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }

    #[Route('/search/{id}', name: 'app_search')]
    public function search($id, EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager): Response 
    {
        // Retrieve the specific event from the search result
        $event = $eventRepository->search1((int)$id);  // Assume search1() is a defined method in your repository
        $events = $eventRepository->findAll();
    
        if (!$event) {
            // Handle the case where no event is found
            return $this->createNotFoundException('No event found for id ' . $id);
        }
    
        // Create a new Ticket entity
        $ticket = new Ticket();
        $ticket->setIdevent($event);
    
        // Create the form and pass idevent and iduser as options
        $form = $this->createForm(TicketFormType::class, $ticket, [
            'action' => $this->generateUrl('app_add_ticket', ['id' => $id, 'idd' => $event->getIdevent()]),
            'method' => 'POST',
        ]);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();
            return $this->redirectToRoute('event_show');
        }
    
        return $this->render('base1.html.twig', [
            'controller_name' => 'TicketController',
            'events' => $events, 
            'event' => $event, 
            'f' => $form->createView(),
        ]);
    }
    #[Route('/Addticket/{eventId}/{userId}', name: 'app_add_ticket')]
    public function addTicket(Request $request, UserRepository $userRepository, EventRepository $eventRepository, EntityManagerInterface $entityManager, int $eventId, int $userId): Response
    {
        $qrDirectory = $this->getParameter('qr_directory');
    
        $event = $eventRepository->find($eventId);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id ' . $eventId);
        }
    
        $user = $userRepository->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $userId);
        }
    
        // Create a new Ticket
        $ticket = new Ticket();
        $ticket->setEvent($event);
        $ticket->setUser($user);
    
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode('TICKET' . uniqid(), $generator::TYPE_CODE_128);
    
        // Specify the directory to save the barcode image
        $barcodeDirectory = 'C:/xampp/htdocs/barcodes/';
    
        // Ensure the directory exists, if not, create it
        if (!is_dir($barcodeDirectory)) {
            mkdir($barcodeDirectory, 0755, true);
        }
    
        $barcodeFilename = 'barcode_' . uniqid() . '.png';
        $barcodePath = $barcodeDirectory . $barcodeFilename;
    
        // Save the barcode image
        if (file_put_contents($barcodePath, $barcode) === false) {
            throw new \RuntimeException("Failed to save the barcode image.");
        }
    
        // Set the path of the barcode image in the QR code field
        $ticket->setQrcode('/barcodes/' . $barcodeFilename);
    
        // Persist the ticket with a valid QR code
        $entityManager->persist($ticket);
        $entityManager->flush();
    
        $this->sendTwilioMessage('+21621494353', $event, $ticket);
    
        $imagesDirectory = $this->getParameter('images_directory');
    
        // Create and handle form submission
        $form = $this->createForm(TicketFormType::class, $ticket);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();  // Final flush after form validation
            return $this->redirectToRoute('ticket/mytickets.html.twig'); // Make sure this route is correctly configured in your routing
        }
    
        // Render form view
        return $this->render('base1.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'user' => $user,
            'images_directory' => $imagesDirectory,
    
            'qr_directory' => $qrDirectory
        ]);}
    
  
private function sendTwilioMessage(string $phoneNumber, Event $event, Ticket $ticket): void
{
    $sid = $this->getParameter('twilio.sid');
    $token = $this->getParameter('twilio.token');
    $twilioPhoneNumber = $this->getParameter('twilio.phone_number');

    $client = new \Twilio\Rest\Client($sid, $token);
    $eventName = $event->getName();
    $eventDate = $event->getDate();  // No format call, using as is since it's a string

    try {
        $message = $client->messages->create(
            $phoneNumber,
            [
                'from' => $twilioPhoneNumber,
                'body' => "Hello! Your ticket for {$eventName} on {$eventDate} has been successfully booked. Your ticket ID is {$ticket->getIdticket()}."
            ]
        );
    } catch (\Exception $e) {
        $this->addFlash('error', 'Failed to send SMS: ' . $e->getMessage());
    }
}
 
#[Route('/view-tickets/{userId}', name: 'view_tickets')]
public function viewTickets(TicketRepository $ticketRepository, int $userId): Response
{ 
    $tickets = $ticketRepository->findBy(['iduser' => $userId]);
    $imagesDirectory = $this->getParameter('images_directory');
    $qrDirectory = $this->getParameter('qr_directory'); // Fetch the QR directory parameter

    if (!$tickets) {
        $this->addFlash('error', 'No tickets found for this user.');
        return $this->redirectToRoute('no_tickets_found_route');
    }

    return $this->render('ticket/mytickets.html.twig', [
        'tickets' => $tickets,
        'images_directory' => $imagesDirectory,
        'qr_directory' => $qrDirectory, // Pass the QR directory to the view
    ]);
}

#[Route('/generate-pdf/{ticketId}', name: 'generate_pdf', methods: ['POST'])]
public function generatePdf(int $ticketId, TicketRepository $ticketRepository, EntityManagerInterface $entityManager): Response
{     $imagesDirectory = $this->getParameter('images_directory');

    $ticket = $ticketRepository->find($ticketId);
    if (!$ticket) {
        $this->addFlash('error', 'Ticket not found.');
        return $this->redirectToRoute('no_tickets_found_route'); // Ensure this route is correctly defined in your routing.
    }

    // Initialize PDF options
    $pdfOptions = new Options();
  
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled', true);  // Enable remote image handling
    $dompdf = new Dompdf($pdfOptions);
    
    // Fetch QR directory parameter
    $qrDirectory = $this->getParameter('qr_directory');

    // Render HTML for PDF from template
    $html = $this->renderView('event/ticket_template_pdf.html.twig', [
        'ticket' => $ticket,
        'qr_directory' => $qrDirectory,
        'images_directory' => $imagesDirectory,

    ]);

    // Load and render PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Save the PDF to a directory on the server
    $pdfFileName = "ticket_{$ticket->getIdticket()}.pdf";
    $pdfFilePath = 'C:\xampp\htdocs\tickets\\' . $pdfFileName; // Adjust the path as needed
    file_put_contents($pdfFilePath, $dompdf->output());

    // Stream the PDF to the browser
    $dompdf->stream($pdfFileName, ["Attachment" => true]);

    // It's usually unnecessary to return a response after streaming a PDF since the `stream()` method sends headers.
    // return new Response('PDF generated successfully!', 200);
    exit; // Ensure no further script execution if not using a Response object or redirect
}
#[Route('/show-tickets', name: 'show_tickets')]
public function showTickets(TicketRepository $ticketRepository): Response
{    
    $tickets = $ticketRepository->findAll();
    $qrDirectory = $this->getParameter('qr_directory');

    if (!$tickets) {
        $this->addFlash('error', 'No tickets found.');
        return $this->redirectToRoute('some_other_route');
    }

    return $this->render('ticket/mytickets.html.twig', [
        'tickets' => $tickets // Ensure this line correctly passes 'tickets'
       , 'qr_directory' => $qrDirectory 
    ]);
}


#[Route('/tickeet', name: 'app_ticket')]
public function indeex(): Response
{
    return $this->render('ticket/mytickets.html.twig', [
        'controller_name' => 'TicketController',
    ]);
}
#[Route('/ticket-delete-show/{userId}', name: 'app_ticketdeleteshow')]
public function deleteTicketShow(int $userId, TicketRepository $ticketRepository): Response
{
    // Fetch tickets by the user ID
    $tickets = $ticketRepository->findBy(['iduser' => $userId]);
    $imagesDirectory = $this->getParameter('images_directory'); // Get the images directory path

    // Check if tickets are found
    if (!$tickets) {
        $this->addFlash('error', 'No tickets found for this user.');
        return $this->redirectToRoute('no_tickets_found_route');
    }

    // Render the view with the tickets and images directory
    return $this->render('ticket/myticketsdelete.html.twig', [
        'tickets' => $tickets,
        'images_directory' => $imagesDirectory // Pass the images directory to the view
    ]);
}

// Correcting the route to properly include both 'ticketId' and 'userId' as parameters
#[Route('/deleteticket/{ticketId}/{userId}', name: 'app_deleteticketsssnow')]
public function deletetickk(ManagerRegistry $doctrine, int $ticketId, int $userId, TicketRepository $rep): Response
{
    $entity = $rep->find($ticketId);
    if (!$entity) {
        $this->addFlash('error', 'No ticket found with ID ' . $ticketId);
        return $this->redirectToRoute('view_tickets', ['userId' => $userId]);  // Include userId in the redirection
    }

    $em = $doctrine->getManager();
    $em->remove($entity);
    $em->flush();

    $this->addFlash('success', 'Ticket deleted successfully.');
    return $this->redirectToRoute('view_tickets', ['userId' => $userId]);  // Include userId in the redirection
}


}
    

