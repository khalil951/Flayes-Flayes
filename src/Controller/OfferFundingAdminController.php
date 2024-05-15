<?php

namespace App\Controller;

use App\Repository\FundingRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/OfferFunding/admin')]
class OfferFundingAdminController extends AbstractController
{
    #[Route('/', name: 'app_offer_funding_admin', methods: ['GET'])]
    public function index(Request $request, OfferRepository $offerRepository): Response
    {
        // Retrieve form data
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');
        $status = $request->query->get('status');

        // Fetch filtered offers based on form inputs
        $offers = $offerRepository->findFilteredOffers($dateFrom, $dateTo, $status);

        // If the request is AJAX, return JSON response
        if ($request->isXmlHttpRequest()) {
            return $this->json($offers, 200, [], ['groups' => 'offer']);
        }

        // If it's a regular request, render the HTML template
        return $this->render('offer_funding_admin/index.html.twig', [
            'offers' => $offers,
        ]);
    }
    #[Route('/sse', name: 'app_offer_funding_admin_sse', methods: ['GET'])]
    public function sse(Request $request, OfferRepository $offerRepository): Response
    {
        // Set the appropriate headers for SSE
        $response = new Response();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        // Create a loop to continuously fetch and send offers
        while (true) {
            // Fetch offers
            $offers = $offerRepository->findAll();

            // Format offers as JSON
            $data = json_encode($offers);

            // Send data to the client
            $response->setContent("data: $data\n\n");
            $response->send();

            // Flush the output buffer to ensure data is sent immediately
            ob_flush();
            flush();

            // Sleep for a short duration to avoid server overload
            usleep(1000000); // Adjust the sleep duration as needed
        }

        return $response;
    }

    #[Route('/{id}/reject', name: 'app_offer_admin_reject')]
    public function adminReject(Request $request, $id , EntityManagerInterface $entityManager,OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        if ($offer) {
            $offer->setStatus(1);
            $entityManager->persist($offer);

            $entityManager->flush();
        }
        else{
            dump($offer);
            die("Error offer not found");
        }
        return $this->redirectToRoute('app_offer_funding_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/accept', name: 'app_offer_admin_accept')]
    public function adminAccept(Request $request, $id , EntityManagerInterface $entityManager,OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        if ($offer) {
            $offer->setStatus(2);
            $entityManager->persist($offer);
            $entityManager->flush();
        }
        else{
            dump($offer);
            die("Error offer not found");
        }
        return $this->redirectToRoute('app_offer_funding_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_offer_admin_delete')]
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
            die("Error offer not found");
        }
        return $this->redirectToRoute('app_offer_funding_admin', [], Response::HTTP_SEE_OTHER);
    }
}
