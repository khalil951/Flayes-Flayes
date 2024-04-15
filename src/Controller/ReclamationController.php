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

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
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

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}



}