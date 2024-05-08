<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Funding;
use App\Entity\Project;
use App\Entity\User;
use App\Form\OfferFundingType;
use App\Repository\FundingRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/OfferFunding')]
class OfferFundingController extends AbstractController
{
    #[Route('/', name: 'app_Offerfunding_index', methods: ['GET'])]
    public function index(OfferRepository $offer , FundingRepository $funding, Request $request, UserRepository $userRepository ,ProjectRepository $projectRepository ,Security $security): Response
    {

        return $this->render('OfferFunding/index.html.twig', [
          'OffersImade' => $offer->findOffersImade($security->getUser()->getId()),
            'OffersIgot' => $offer->findOffersIgot($security->getUser()->getId()),
            'fundings' => $funding->findAll()
        ]);
    }
//    this function
    #[Route('/new', name: 'app_OfferFunding_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , UserRepository $userRepository ,Security $security): Response
    {
//       Retrieving User here

        $user =$userRepository->find($security->getUser()->getId());
//      End retrieving User here
        $offer = new Offer();
        $funding = new Funding();
        $form = $this->createForm(OfferFundingType::class,  $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $formData->setDateCreated(new \DateTime());
            $formData->setStatus(0);

            $offer->setUser($user);

//          Static adding project and reciever


            $offer->setReciever($userRepository->find(97));

            $funding=$formData->getFunding();
            $this->calculateOfferScore($funding);

            $entityManager->persist($offer);
            $entityManager->persist($funding);
            $entityManager->flush();

            return $this->redirectToRoute('app_Offerfunding_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('OfferFunding/new.html.twig', [
            'Offer' => $offer,
            'Funding' => $funding,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_OfferFunding_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id , EntityManagerInterface $entityManager): Response
    {

        $offer = $entityManager->getRepository(Offer::class)->find($id);
        $funding = $offer->getFunding();
        $offer->setStatus(0);
        $this->calculateOfferScore($funding);

        if (!$offer) {
            throw $this->createNotFoundException('The offer does not exist');
        }

        $form = $this->createForm(OfferFundingType::class, $offer, [
            'funding' => $funding,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_Offerfunding_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('OfferFunding/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_offer_delete')]
    public function delete(Request $request, $id , EntityManagerInterface $entityManager,OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        if ($offer) {
            $entityManager->remove($offer);
            $entityManager->remove($offer->getFunding());
            $entityManager->flush();
        }
        else{
            dump($offer);
            die("Error where");
        }
        return $this->redirectToRoute('app_Offerfunding_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/reject', name: 'app_offer_reject')]
    public function Reject(Request $request, $id , EntityManagerInterface $entityManager,OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        if ($offer) {
            $offer->setStatus(4);
            $entityManager->persist($offer);

            $entityManager->flush();
        }
        else{
            dump($offer);
            die("Error offer not found");
        }
        return $this->redirectToRoute('app_Offerfunding_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/accept', name: 'app_offer_accept')]
    public function Accept(Request $request, $id , EntityManagerInterface $entityManager,OfferRepository $offerRepository , MailerService $mailer): Response
    {
        $offer = $offerRepository->find($id);
        $reciever=$entityManager->getRepository(User::class)->find($offer->getUser());

        $mailer->sendEmail($offer , $reciever);
        if ($offer) {
            $offer->setStatus(3);
            $entityManager->persist($offer);

            $entityManager->flush();

        }
        else{
            dump($offer);
            die("Error offer not found");
        }

        return $this->redirectToRoute('app_Offerfunding_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/checkout', name: 'app_offer_pay')]
    public function checkout(Request $request, $id ,OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);
        $funding=$offer->getFunding();
        $stripe = new \Stripe\StripeClient('sk_test_51OqZ3YLdtRvtorIBI6PHfTH7iGGb64aLqBG7z3jIEGlSmui5sNxJ3mjr8GsKxg3dDFP6L0O9dx5L3PbOwFhrhXRi00LLRcqQUf');

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $offer->getTitle(),
                    ],
                    'unit_amount' => $funding->getAttribute1(),
                ],
                'quantity' => $funding->getAttribute2(),
            ]],
            'mode' => 'payment',
            'success_url'          => $this->generateUrl('success_url', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($checkout_session->url , 303);
    }
    #[Route('/success-url/{id}', name: 'success_url')]
    public function successUrl($id, EntityManagerInterface $entityManager): Response
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);

        // Set offer status to 5 (successful payment)
        $offer->setStatus(5);
        $entityManager->flush();

        return $this->render('OfferFunding/payment.html.twig', [
            'message' => 'successfull',
        ]);
    }

    #[Route('/cancel-url/{id}', name: 'cancel_url')]
    public function cancelUrl($id, EntityManagerInterface $entityManager): Response
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);

        // Set offer status to 6 (payment error)
        $offer->setStatus(6);
        $entityManager->flush();

        return $this->render('OfferFunding/payment.html.twig', [
            'message' => 'error',
        ]);
    }
    private function calculateOfferScore(Funding $funding) {
        switch ($funding->getType()) {
            case 'dept':
                switch ($funding->getTextattribute()) {
                    case 'AAA':
                        $riskScore = 100;
                        break;
                    case 'AA+':
                        $riskScore = 90;
                        break;
                    case 'AA':
                        $riskScore = 80;
                        break;
                    case 'A+':
                        $riskScore = 70;
                        break;
                    case 'A':
                        $riskScore = 60;
                        break;
                    case 'BBB+':
                        $riskScore = 50;
                        break;
                    case 'BBB':
                        $riskScore = 40;
                        break;
                    case 'BB+':
                        $riskScore = 30;
                        break;
                    case 'BB':
                        $riskScore = 20;
                        break;
                    default:
                        $riskScore = 0; // Default score for unknown risk appetite
                }

                $score = ($funding->getAttribute1() * 0.4) +($funding->getAttribute2() * 0.3 ) + ($funding->getAttribute3() * 0.2) + ($riskScore * 0.1);

                break;
            case 'revenue':
                switch($funding->getTextattribute()){
                    case'On sails':
                        $score=($funding->getAttribute1() * 0.4)+ ($funding->getAttribute3() * 0.3);
                        break;
                    case 'On revenue':
                        $score=($funding->getAttribute1() * 0.4)+ ($funding->getAttribute2() * 0.3);
                        break;
                }
                 break;
            case 'equity':
                switch ($funding->getTextattribute()) {
                    case 'Low':
                        $riskScore = 40;
                        break;
                    case 'Medium':
                        $riskScore = 30;
                        break;
                    case 'High':
                        $riskScore = 20;
                        break;
                    default:
                        $riskScore = 0; // Default score for unknown risk appetite
                }
                $score=($funding->getAttribute1() * 0.4)  + ($funding->getAttribute2() * 0.3)   + ($funding->getAttribute3() * 0.2) + ($riskScore * 0.1);
                 break;
        }
        $funding->setScore($score);
    }



}
