<?php

namespace App\Controller;

use App\Entity\PostReact;
use App\Form\PostReactType;
use App\Repository\PostReactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post/react')]
class PostReactController extends AbstractController
{
    #[Route('/', name: 'app_post_react_index', methods: ['GET'])]
    public function index(PostReactRepository $postReactRepository): Response
    {
        return $this->render('post_react/index.html.twig', [
            'post_reacts' => $postReactRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_react_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postReact = new PostReact();
        $form = $this->createForm(PostReactType::class, $postReact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postReact);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_react_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_react/new.html.twig', [
            'post_react' => $postReact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_react_show', methods: ['GET'])]
    public function show(PostReact $postReact): Response
    {
        return $this->render('post_react/show.html.twig', [
            'post_react' => $postReact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_react_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PostReact $postReact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostReactType::class, $postReact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_react_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_react/edit.html.twig', [
            'post_react' => $postReact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_react_delete', methods: ['POST'])]
    public function delete(Request $request, PostReact $postReact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postReact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($postReact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_react_index', [], Response::HTTP_SEE_OTHER);
    }
}
