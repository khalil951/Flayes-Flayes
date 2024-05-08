<?php

namespace App\Controller;


use App\Entity\Post;
use App\Entity\PostReact;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostReactRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;



#[Route('/room/{room_id}/posts')]
class PostController extends AbstractController
{
    // #[Route('/', name: 'app_post_index', methods: ['GET'])]
    // public function index(PostRepository $postRepository): Response
    // {
    //     return $this->render('post/index.html.twig', [
    //         'posts' => $postRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RoomRepository $roomRepository, int $room_id , UserRepository $userRepository, Security $security): Response
    {
    $post = new Post();

    $room = $roomRepository->find($room_id); 
    $form = $this->createForm(PostType::class, $post);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
         // Handle file upload for profile picture
         $file = $form->get('img_url')->getData();
         if ($file instanceof UploadedFile) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
             try {
                 $file->move('/Applications/XAMPP/xamppfiles/htdocs/uploads' , $fileName); 
                 $post->setImgUrl($fileName);
             } catch (FileException $e) {
                 // Handle file upload error
                 $this->addFlash('error', 'Failed to upload the file.');
                 // Redirect to the new post form with the room_id parameter
                return $this->redirectToRoute('app_post_new', ['room_id' => $room_id]);
             }
            }

         // Get the currently logged-in user
         $user = $security->getUser();
         // Set the room for the post
         $post->setRoom($room);
         if ($user) {
             // Set the user ID if user is logged in
             $post->setUserId($user->getId());
         }
        //VarDumper::dump($post);
        $entityManager->persist($post);
        $entityManager->flush();
        // Redirect to the room index page after successfully creating the post
        return $this->redirectToRoute('app_room_show_posts', ['id' => $room_id], Response::HTTP_SEE_OTHER);
    }

    // Render the new post form with the associated room
    return $this->renderForm('post/new.html.twig', [
        'post' => $post,
        'room' => $room,
        'form' => $form,
    ]);
}

    
    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        $room = $post->getRoom();
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'room' => $room,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, int $room_id): Response
    {
        // Fetch the room associated with the post
        $room = $post->getRoom();
    
        // Create the form with the specific room associated with the post
        $form = $this->createForm(PostType::class, $post);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img_url')->getData();
             if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                 try {
                     $file->move('/Applications/XAMPP/xamppfiles/htdocs/uploads' , $fileName); 
                     $post->setImgUrl($fileName);
                 } catch (FileException $e) {
                     // Handle file upload error
                     $this->addFlash('error', 'Failed to upload the file.');
                     return $this->redirectToRoute('app_post_edit', ['id' => $post->getId()]);
                 }
                }
    
            $entityManager->flush();
            return $this->redirectToRoute('app_room_show_posts', ['id' => $room_id], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
            'room' => $room,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }
        $room_id = $post->getRoom()->getId();
        return $this->redirectToRoute('app_room_show_posts', ['id' => $room_id], Response::HTTP_SEE_OTHER);
    }


    // public function isLikedByUser(User $user) : bool {
    //     foreach($this->likes as $like){
    //         if ($like->getUser() == $user) return true;
    //     }
    //     return false;
    // }

    // #[Route('/{id}/like', name: 'app_post_like')]
    // public function like(Post $post, EntityManagerInterface $entityManager, PostReactRepository $postReactRepository): Response
    // {
    //    // $user = $this->getUser();

    //     // if (!$user) return $this->json([
    //     //     'code' => 403,
    //     //     'message' => 'unauthorized'
    //     // ],403);
        
    //     // if ($post->isLikedByUser($user)) {
    //     //     // If liked, find the corresponding PostReact entity
    //     //     $like = $postReactRepository->findOneBy([
    //     //         'post' => $post,
    //     //         'user' => $user
    //     //     ]);
            
    //     //     // Now you can use the $like entity as needed
    //     //     // For example, you can remove the like if you want
    //     //     if ($like) {
    //     //         $entityManager->remove($like);
    //     //         $entityManager->flush();
    //     //     }
    //         //return $this->json(['code' => 200, 'message' => 'Post unliked successfully' , 'likes' => $postReactRepository->count(['post' => $post]) ], 200);
    //     // }

    //     $like = new PostReact();
    //     $like->setPost($post);
    //       //  ->setUser($user);

    //     $entityManager->persist($like);
    //     $entityManager->flush();

    //     // For demonstration purposes, let's assume we're just returning a JSON response
    //     return new Response(json_encode(['code' => 200, 'message' => 'Liked added' , 'likes' => $postReactRepository->count(['post' => $post])]), 200, ['Content-Type' => 'application/json']);   
    // }


    #[Route('/{id}/like', name: 'app_post_like')]
    public function like(Post $post, EntityManagerInterface $entityManager, PostReactRepository $postReactRepository, Security $security): Response
    {
        // Check if the post is already liked
        if ($post->isLiked($entityManager)) {
            // If liked, find the corresponding PostReact entity
            $like = $postReactRepository->findOneBy([
                'post' => $post
            ]);
            
            // If like exists, remove it
            if ($like) {
                $entityManager->remove($like);
                $entityManager->flush();
            }
            return $this->json(['code' => 200, 'message' => 'Post unliked successfully', 'likes' => $postReactRepository->count(['post' => $post])], 200);
        }

        // If not liked, create a new PostReact entity to represent the like
        $like = new PostReact();
        $like->setPost($post);
        $user_id = $security->getUser()->getId();
        $like->setUserId($user_id);
        // $like->setIsliked(true);
        // Persist and flush the like entity
        $entityManager->persist($like);
        $entityManager->flush();
        $post->setNumLikes($postReactRepository->count(['post' => $post]));
        // Return a JSON response indicating success and the updated like count
        return $this->json(['code' => 200, 'message' => 'Post liked successfully', 'likes' => $postReactRepository->count(['post' => $post])], 200);
    }


}