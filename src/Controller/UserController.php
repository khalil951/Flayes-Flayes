<?php

//require_once './vendor/autoload.php';

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RoomRepository;
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
use Symfony\Component\Security\Core\Security;
use App\Repository\EventRepository;
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
use Knp\Component\Pager\PaginatorInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Tool\RequestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

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

    

    
    #[Route('/msg', name: 'app_user')]
    public function index(Request $request): Response
    {  
        return $this->render('user/msg.html.twig');
    }

    #[Route('/home', name: 'app_home')]
    public function home(Request $request): Response
    {  
        return $this->render('home/index.html.twig');
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(Request $request): Response
    {  
        return $this->render('back.html.twig');
    }

    #[Route('/forum', name: 'app_admin_forum')]
    public function stats(ChartBuilderInterface $chartBuilder, RoomRepository $roomRepository): Response
    {
        $roomCounts = $roomRepository->getRoomCountsPerCategory();    
        // Extract the top 3 categories
        arsort($roomCounts);
        $topRoomCategories = array_slice($roomCounts, 0, 3);
        dump($topRoomCategories);
        
        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        
        $data = [
            'labels' => array_keys($topRoomCategories), 
            'datasets' => [
                [
                    'label' => 'Room Categories',
                    'backgroundColor' => ['#00cca2', '#8d16e8', '#ef764c'],
                    'data' => array_values($topRoomCategories),
                ],
            ],
        ];
        
        $chart->setData($data);
    
        // Add any custom options here if needed
    
        return $this->render('admin/forum.html.twig', [
            'chart' => $chart,
        ]);
    }


 

    #[Route('/{id}/update', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload

        // Persist changes to the user entity
        $entityManager->flush();

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/profile.html.twig', [
        'user' => $user,
        'form' => $form, // Pass the form directly, not as a FormView
    ]);
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
    return $this->redirectToRoute('app_user');
}

return $this->render('user/test.html.twig', [
    'registrationForm' => $form->createView(),
    'user' => $user,
]);
}

#[Route('/admin/list', name: 'app_list')]
    public function list(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch all users from the UserRepository
        $users = $userRepository->findAll();

        // Paginate the results
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1), // Get the page number from the request, default to 1
            3 // Number of items per page
        );

        return $this->render('user/index.html.twig', [
            'users' => $pagination,
           
        ]);
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
        return $this->redirectToRoute('app_home');
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

                 // Generate absolute URL to the image
            // Get the base URL of your Symfony application
            $baseUrl = $request->getSchemeAndHttpHost();
            // Define the absolute URL of the image directly
         $imageUrl = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTQZXYjiy-qY1mjlMTPBx3aOKKYCUFhV8PfA&s';
            
            
            $htmlContent = "
                <html>
                <body>
                    <p>Dear " . $user->getName() . ",</p>
                    <p>To reset your password, please visit the following link: <a href='" . $url . "'>" . $url . "</a></p>
                    <img src='" . $imageUrl . "' alt='Flayes Presentation Image'>
                    If you have any questions or need assistance, feel free to contact us at support@flayes.com

Follow us on Twitter | Like us on Facebook
                </body>
                </html>
            ";



                $context = compact('url', 'user');
                $email = (new TemplatedEmail())
                ->from('iben46655@gmail.com')
                ->to($email)
                ->subject('Reset Password !')
                ->html($htmlContent);

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



    
    #[Route('/user/{id}/ban', name:'ban_user')]
    public function banUser(User $user, MailerInterface $mailer): Response
    {
        // Ban the user (update user status, set a flag, etc.)
        $user->setStatus(2);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Send email notification to the user
        $email = (new Email())
            ->from('iben46655@gmail.com')
            ->to($user->getEmail())
            ->subject('Your account has been banned')
            ->html('Dear ' . $user->getName() . ',<br>Your account has been banned. If you believe this is a mistake, please contact support.');

        $mailer->send($email);

        // Redirect back to the user profile or any other appropriate page
        return $this->redirectToRoute('app_list', ['id' => $user->getId()]);
    }


    
    #[Route('/user/{id}/deactivate', name:'user_deactivate_ban')]
    public function deactivateBan(User $user, MailerInterface $mailer): Response
    {
        // Assuming 'status' field represents the ban status in your User entity
        $user->setStatus(1); // Assuming 1 represents an active user
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Send an email to the user notifying them that their ban has been deactivated
        $email = (new Email())
            ->from('iben46655@gmail.com')
            ->to($user->getEmail())
            ->subject('Your account ban has been deactivated')
            ->html('Dear ' . $user->getName() . ',<br>Your account ban has been deactivated. You can now access your account again.');

        $mailer->send($email);

        // Optionally, add a flash message to indicate successful deactivation
        $this->addFlash('success', 'User ban deactivated successfully.');

        // Redirect the user to a relevant page
        return $this->redirectToRoute('app_list', ['id' => $user->getId()]);
    }
    
    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request): Response
    {  
        return $this->render('user/profile.html.twig');
    }

   
    #[Route('/connect/google', name:'connect_google_star')]
   public function connectAction(ClientRegistry $clientRegistry)
   {
       // will redirect to Facebook!
       return $clientRegistry
           ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
           ->redirect();
   }

   /**
    * @Route("/connect/google/check", name="connect_google_check")
    * @param Request $request
    * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function connectCheckAction(Request $request )
    {
        if(!$this->getUser()){
            return new JsonResponse(array('status' => false,'message'=>"user not found"));
        } else {
            return $this->redirectToRoute('app_login');
        }
    }



  
    #[Route('/capture', name: 'capture_photo')]
public function capturePhoto(Request $request, Security $security): Response
{
    // Get the image directory path from Symfony configuration
    $imageDirectory = $this->getParameter('image_directory');

    // Ensure that the image directory path ends with a trailing slash
    $imageDirectory = rtrim($imageDirectory, '/') . '/';

    // Get the image data from the request
    $imageData = $request->request->get('image');

    // Split the base64-encoded image data into its parts
    $image_parts = explode(";base64,", $imageData);

    // Extract the image type from the data
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];

    // Decode the base64-encoded image data
    $image_base64 = base64_decode($image_parts[1]);

    // Generate a unique filename for the image and save it to the specified folder
    $fileName = uniqid() . '.png';
    $filePath = $imageDirectory . $fileName;
    file_put_contents($filePath, $image_base64);

    // Update the authenticated user's imageName
    $user = $security->getUser();
    if ($user) {
        $user->setImageName($fileName);
        $this->getDoctrine()->getManager()->flush();
    }

    // Return a JSON response indicating the success of the upload
    return new JsonResponse(["message" => "Image uploaded successfully."]);
}



    #[Route('/capture-page', name: 'capture_page')]
    public function capturePage(): Response
    {
        return $this->render('user/capture.html.twig');
    }


  /**
 * @Route("/upload-image", name="upload_image", methods={"GET", "POST"})
 */
public function uploadImageForm(Request $request, Security $security): Response
{
    // Get the authenticated user
    $user = $security->getUser();
    
  
    
    return $this->render('user/upload_image.html.twig');
}

/**
 * @Route("/update-image-url", name="update_image_url", methods={"POST"})
 */
public function updateImageUrl(Request $request, Security $security)
{
    // Get the authenticated user
    $user = $security->getUser();

    // Parse the JSON request body
    $data = json_decode($request->getContent(), true);

    // Check if the imageUrl key exists in the data
    if (isset($data['imageUrl'])) {
        // Set the image URL for the user entity
        $user->setImageName($data['imageUrl']);
        // Save the changes to the database
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['success' => true]);
    } else {
        return new JsonResponse(['error' => 'Image URL not found in the request'], 400);
    }
}



}