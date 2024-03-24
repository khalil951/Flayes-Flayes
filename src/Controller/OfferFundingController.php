<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Funding;
use App\Form\OfferFundingType;
use App\Repository\FundingRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/OfferFunding')]
class OfferFundingController extends AbstractController
{
    #[Route('/', name: 'app_offer_index', methods: ['GET'])]
    public function index(OfferRepository $offer ,FundingRepository $funding): Response
    {
        return $this->render('OfferFunding/index.html.twig', [
          'offers' => $offer->findAll(),
            'fundings' => $funding->findAll()
        ]);
    }
    #[Route('/new', name: 'app_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();
        $funding = new Funding();
        $form = $this->createForm(OfferFundingType::class, (array)[$offer,$funding]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $formData->setDateCreated(new \DateTime());
            dump($formData);
            $entityManager->persist($offer);
            $entityManager->flush();

//            return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('OfferFunding/new.html.twig', [
            'OfferFunding' => $offer,
            'form' => $form,
        ]);
    }
}
