<?php

//require_once './vendor/autoload.php';

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;

use App\Security\EmailVerifier;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface as AuthenticationUserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Authenticator\UserAuthenticatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Event\ListAllUsersEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class UserController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer,private EventDispatcherInterface $dispatcher)
    {
        $this->mailer = $mailer;
        
    }

   
   /* private EmailVerifier $emailVerifier;

public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }*/

    

    
    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {  
        return $this->render('user/msg.html.twig');
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(Request $request): Response
    {  
        return $this->render('back.html.twig');
    }



    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {    
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
                 //image upload
            


// Récupérez le fichier de l'image à partir du formulaire
$imageFile = $form->get('imageFile')->getData();
        
// Vérifiez s'il y a un fichier d'image
if ($imageFile) {
    // Définissez le nom de l'image sur l'entité Event
    $user->setImageFile($imageFile);
    // Persistez l'entité Event
    $entityManager->persist($user);
    // Flush pour enregistrer l'entité dans la base de données
    $entityManager->flush();
}
    
            // Hash the password
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);
    
            // Save the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirect to a success page or login page
           // return $this->redirectToRoute('app_register');
           $expiresAtMessageKey = 'email.confirmation.expires';
           $expiresAtMessageData = ['expiration_time' => '10 minutes']; // Example data, replace with actual data
           
           // Send email confirmation
           $transport = Transport::fromDsn('smtp://iben46655@gmail.com:hvgetegqlqdnzola@smtp.gmail.com:587');

           // Create a Mailer object
           $mailer = new Mailer($transport);
           
           // Create an Email object
           $email = (new Email());
           
           // Set the "From address"
           $email->from('iben46655@gmail.com');
           
           // Set the "To address"
           $email->to(
            $user->getEmail()
           );
           
           # $email->cc('cc@example.com');
           // Set "BCC"
           # $email->bcc('bcc@example.com');
           // Set "Reply To"
           # $email->replyTo('fabien@example.com');
           // Set "Priority"
           # $email->priority(Email::PRIORITY_HIGH);
           
           // Set a "subject"
           $email->subject('A Cool Subject!');
           
           // Set the plain-text "Body"
           $email->text('The plain text version of the message.');
           
           // Set HTML "Body"


           $htmlBody = $this->renderView('user/confirmation_email.html.twig', [
            'signedUrl' => $this->generateUrl('confirm_email', ['userId' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL), // Generate the absolute URL for the confirm_email route
            'expiresAtMessageKey' => $expiresAtMessageKey,
            'expiresAtMessageData' => $expiresAtMessageData,
            'user' => $user,
        ]);
        
        // Set the HTML body of the email
        $email->html($htmlBody);
           /*$email->html("
           <h1>Hi! Please confirm your email!</h1>
           <p>
               Please confirm your email address by clicking the following link: <br><br>
               <a href=\"{{ signedUrl }}\">Confirm my Email</a>.
               This link will expire in {{ expiresAtMessageKey|trans(expiresAtMessageData, 'VerifyEmailBundle') }}.
           </p>
           <p>
               Cheers!
           </p>
       ");*/
           
           // Add an "Attachment"
           //$email->attachFromPath('example_1.txt');
          // $email->attachFromPath('example_2.txt');
           
           // Add an "Image"
           //$email->embed(fopen('image_1.png', 'r'), 'Image_Name_1');
          // $email->embed(fopen('image_2.jpg', 'r'), 'Image_Name_2');
           
           // Sending email with status
           try {
               // Send email
               $mailer->send($email);
               
               // Display custom successful message
               return $this->redirectToRoute('app_user');
           } catch (TransportExceptionInterface $e) {
               // Display custom error message
               die('<style>* { font-size: 100px; color: #fff; background-color: #ff4e4e; }</style><pre><h1>&#128544;Error!</h1></pre>');
           
               // Display real errors
               # echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
           }

        // Redirect to a success page or login page
        return $this->redirectToRoute('app_user');
    }

    return $this->render('user/signup.html.twig', [
        'registrationForm' => $form->createView(),
        'user' => $user,
    ]);
    }
    

  




    #[Route('/confirm-email/{userId}', name: 'confirm_email')]
public function confirmEmail(Request $request, $userId): Response
{      
    // Fetch the user from the database based on $userId
    $entityManager = $this->getDoctrine()->getManager();
    $user = $entityManager->getRepository(User::class)->find($userId);
    $signedUrl = $this->generateUrl('confirm_email', ['userId' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL); // Generate the absolute URL for the confirm_email route
    $expiresAtMessageKey = 'email.confirmation.expires';
    $expiresAtMessageData = ['expiration_time' => '10 minutes']; // Example data, replace with actual data
    // If user not found, handle error or redirect to appropriate page
    if (!$user) {
        // Handle error or redirect
        // For example, redirect to a 404 page
        throw $this->createNotFoundException('User not found');
    }

    // Update user status to "1"
    $user->setStatus(1);
    $entityManager->flush();

    // Add flash message
    $this->addFlash('success', 'Your email has been verified successfully! You can now log in.');

    // Render the confirmation email template
    $htmlBody = $this->renderView('user/confirmation_email.html.twig', [
        'signedUrl' => $signedUrl,
        'expiresAtMessageKey' => $expiresAtMessageKey,
        'expiresAtMessageData' => $expiresAtMessageData,
        'user' => $user,
    ]);

    // Create a response object with the rendered view
    return $this->redirectToRoute( 'app_login' );
}


#[Route('/add', name: 'app_add' )]
public function add(Request $request, EntityManagerInterface $entityManager): Response
{    
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission

        // Persist user entity
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirect to a success page or login page
        return $this->redirectToRoute('app_list');
    }

    return $this->render('user/test.html.twig', [
        'userForm' => $form->createView(),
        'user' => $user,
    ]);
}

#[Route('/admin/list', name: 'app_list')]
public function list(ManagerRegistry $doctrine, Request $request): Response {
    $repository = $doctrine->getRepository(User::class);
    $users = $repository->findAll();
    return $this->render('admin/index.html.twig', ['users' => $users]);
}



#[
    Route('/alls/{page?1}/{nbre?12}', name: 'user.list.alls'),
    
]
public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response {
//        echo ($this->helper->sayCc());
    $repository = $doctrine->getRepository(User::class);
    $nbUser = 1;
    // 24
    $nbrePage = ceil($nbUser / $nbre) ;

    $users = $repository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
    $ListAllUsersEvent = new ListAllUsersEvent(count($users));
    $this->dispatcher->dispatch($ListAllUsersEvent, ListAllUsersEvent::LIST_ALL_USER_EVENT);

    return $this->render('admin/index.html.twig', [
        'users' => $users,
        'isPaginated' => true,
        'nbrePage' => $nbrePage,
        'page' => $page,
        'nbre' => $nbre
    ]);
}

#[Route('/{id<\d+>}', name: 'user.detail')]
    public function detail(User $user = null): Response {
        if(!$user) {
            $this->addFlash('error', "La personne n'existe pas ");
            return $this->redirectToRoute('user.list');
        }

        return $this->render('admin/detail.html.twig', ['user' => $user]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $imageFile = $form->get('imageFile')->getData();
        
        // Check if there is a new image file
        if ($imageFile) {
            // Set the image file on the user entity
            $user->setImageFile($imageFile);
        }

        // Persist changes to the user entity
        $entityManager->flush();

        return $this->redirectToRoute('app_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin/edit.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}


    

    #[
        Route('/delete/{id}', name: 'user.delete'),
        
    ]
    public function deletePersonne(User $user = null, ManagerRegistry $doctrine): Response {
        // Récupérer la personne
        if ($user) {
            // Si la personne existe => le supprimer et retourner un flashMessage de succés
            $manager = $doctrine->getManager();
            // Ajoute la fonction de suppression dans la transaction
            $manager->remove($user);
            // Exécuter la transacition
            $manager->flush();
            $this->addFlash('success', "La personne a été supprimé avec succès");
        } else {
            //Sinon  retourner un flashMessage d'erreur
            $this->addFlash('error', "Personne innexistante");
        }
        return $this->redirectToRoute('app_list');
    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/signin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }





    #[Route('/forgotten', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());
    
            if($user){
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
                
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);


                $emailBody = "To reset your password, please visit the following link: $url";


                $context = compact('url', 'user');
                $email = (new TemplatedEmail())
                ->from('iben46655@gmail.com')
                ->to($email)
                ->subject('Reset Password !')
                ->text($emailBody);

            $mailer->send($email);
    
                    $this->addFlash('success', 'Email sent successfully! Please check your email to reset your password.');
                    return $this->redirectToRoute('app_login');


            }
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
                
        }
    
        return $this->render('user/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }
    
                  
                   
    #[Route('/forgotten/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);
        
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password updated succefully !');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('user/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }


    
}