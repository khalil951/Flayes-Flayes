<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Entity\User;
class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
       // Generate the signature for the email confirmation
       $signatureComponents = $this->verifyEmailHelper->generateSignature(
        $verifyEmailRouteName,
        $user->getId(), // User ID
        "bennourines00@gmail.com" // User email
    );

       // Add signature components to the email context
       $context = $email->getContext();
       $context['signedUrl'] = $signatureComponents->getSignedUrl();
       $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
       $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
       $email->context($context);

       // Send the email
       $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        // Validate the email confirmation
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        // Persist the user in the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
