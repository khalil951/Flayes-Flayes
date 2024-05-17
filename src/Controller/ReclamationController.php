<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
{
    // Fetch reclamations ordered by etat (Not Treated first)
    $reclamations = $reclamationRepository->findBy([], ['etat' => 'ASC']);

    return $this->render('reclamation/index.html.twig', [
        'reclamations' => $reclamations,
    ]);
}


#[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
public function new(Request $request,PaginatorInterface $paginator, EntityManagerInterface $entityManager,ReclamationRepository $reclamationRepository): Response
{
    $reclamation = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $count = $reclamationRepository->countRecentReclamations($this->getUser()->getId(), 3);
        if ($count >= 3) {
            // Redirect the user back to the new reclamation page with an error message
            $this->addFlash('error', 'You have already submitted the maximum number of reclamations allowed in the last 3 days.');
            $reclamations=$reclamationRepository->findByidUser(1);
   
            $pagination = $paginator->paginate(
                $reclamations,
                $request->query->getInt('page', 1),
                3
            );
            return $this->redirectToRoute('app_reclamation_index_front', [
                'reclamations' => $pagination], Response::HTTP_SEE_OTHER
          );
        }
        $reclamation->setEtat("Not Treated");
        $d=new \DateTimeImmutable();
        $reclamation->setDate($d);
        $reclamation->setIdUser($this->getUser()->getId());
        $cleaned=\ConsoleTVs\Profanity\Builder::blocker($reclamation->getDescription())->filter();
        $reclamation->setDescription($cleaned);
        $entityManager->persist($reclamation);
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}

#[Route('/Front', name: 'app_reclamation_index_front', methods: ['GET'])]
    public function indexFront(PaginatorInterface $paginator,ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager,Request $request): Response
    {
        $reclamations=$reclamationRepository->findByidUser($this->getUser()->getId());
       
        $pagination = $paginator->paginate(
            $reclamations,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $pagination,
        ]);
    }
    
    #[Route('/{id}/modif', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
{
    // Check if the CSRF token is valid
    if ($this->isCsrfTokenValid('delete'.$reclamation->getIdRec(), $request->request->get('_token'))) {
        // Remove the reclamation entity
        $entityManager->remove($reclamation);
        // Flush changes to the database
        $entityManager->flush();
    }
    
    // Check if the 'front' parameter is set in the request
    $check = $request->request->get('front');
    if ($check == 1) {
        // Redirect to the front index route if 'front' is set to 1
        return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
    } else {
        // Redirect to the default index route otherwise
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}

#[Route('/{id}/delete', name: 'app_reclamation_delete', methods: ['POST'])]
public function deleteReclamation(Request $request, $id): Response
    {
        // Retrieve the EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find the reclamation entity by ID
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        // Check if the reclamation exists
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        // Delete the reclamation
        $entityManager->remove($reclamation);
        $entityManager->flush();

        // After successful deletion, redirect to reclamation/index.html.twig
        return $this->redirectToRoute('app_reclamation_index');
    }
    #[Route('/{id}/rep', name: 'send_email', methods: ['POST'])]
    public function sendEmail(int $id, Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        
        // Retrieve reclamation object from the database based on $id
    $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
    $userId = $reclamation->getIdUser();

// Retrieve the user entity using Doctrine's entity manager
$userRepository = $this->getDoctrine()->getRepository(User::class); // Replace User::class with your actual User entity class
$user = $userRepository->find($userId);
    
        // Check if reclamation exists
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
    
        
    
        // Get response from the form
        $response = $request->request->get('response');
    
        // Create HTML content for the email
        $emailContent = "
            <html>
            <body>
                <p>Dear " . $user->getName() . ",</p>
                <p>Regarding your complaint:</p>
                <blockquote>" . $reclamation->getDescription() . "</blockquote>
                <p>We have processed your complaint and here is our response:</p>
                <blockquote>" . $response . "</blockquote>
                <p>Thank you for reaching out to us.</p>
            </body>
            </html>
        ";
    
        // Create and send email
        $transport = Transport::fromDsn('smtp://iben46655@gmail.com:hvgetegqlqdnzola@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from('iben46655@gmail.com')
            ->to($user->getEmail())
            ->subject('Regarding Your Complaint')
            ->html($emailContent);
    
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Handle email sending exception here
            // You may log the error or show an error message to the user
            return new Response('Failed to send email: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        // Update reclamation status
        $reclamation->setEtat('Treated');
        $reclamation->setResponse($response); // Set the response in the reclamation object
        $entityManager->flush();
    
        // Redirect back to wherever you want
        return $this->redirectToRoute('app_reclamation_index');
    }

    #[Route('/{id}/rat', name: 'app_reponse_show', methods: ['GET','POST'])]
public function show(Reclamation $reclamation, Request $request): Response
{
    // Initialize variables
    $check = 0;
    $form = $this->createFormBuilder()
        ->add('note', ChoiceType::class, [
            'label' => 'Rating',
            'choices' => [
                '1 star' => 1,
                '2 stars' => 2,
                '3 stars' => 3,
                '4 stars' => 4,
                '5 stars' => 5,
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $stars = $form->getData()['note'];
        // Add rating to reclamation
        $reclamation->addRating($stars);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();

        // Redirect to the same page to prevent form resubmission
        return $this->redirectToRoute('app_reponse_show', ['id' => $reclamation->getIdRec()]);
    }

    // Calculate average rating
   
    // Render the response show page
    return $this->render('reclamation/show.html.twig', [
        'reclamation' => $reclamation,
        'check' => $check,
        'rating_form' => $form->createView(),
    ]);
}

}