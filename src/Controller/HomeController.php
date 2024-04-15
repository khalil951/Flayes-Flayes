<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/affilate', name: 'app_affilate')]
    public function affilate(): Response
    {
        return $this->render('home/affiliate.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/signup', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('user/Registration.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/signin', name: 'app_login')]
    public function loginr(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/plan', name: 'app_plan')]
    public function plan(): Response
    {
        return $this->render('home/plan.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
