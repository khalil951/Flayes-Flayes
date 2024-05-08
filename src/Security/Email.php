<?php
// Load Composer's autoloader
require_once './vendor/autoload.php';

// Import Classess
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Create a Transport object
$transport = Transport::fromDsn('smtp://iben46655@gmail.com:hvgetegqlqdnzola@smtp.gmail.com:587');

// Create a Mailer object
$mailer = new Mailer($transport);

// Create an Email object
$email = (new Email());

// Set the "From address"
$email->from('iben46655@gmail.com');

// Set the "To address"
$email->to(
    'email1@gmail.com'
    # 'email2@gmail.com',
    # 'email3@gmail.com'
);

// Set "CC"
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
$email->html('
    <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
    The HTML version of the message.
    </h1>
    <img src="cid:Image_Name_1" style="width: 600px; border-radius: 50px">
    <br>
    <img src="cid:Image_Name_2" style="width: 600px; border-radius: 50px">
    <h1 style="color: #ff0000; background-color: #5bff9c; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
    The End!
    </h1>
');

// Add an "Attachment"
$email->attachFromPath('example_1.txt');
$email->attachFromPath('example_2.txt');

// Add an "Image"
$email->embed(fopen('image_1.png', 'r'), 'Image_Name_1');
$email->embed(fopen('image_2.jpg', 'r'), 'Image_Name_2');

// Sending email with status
try {
    // Send email
    $mailer->send($email);

    // Display custom successful message
    die('<style> * { font-size: 100px; color: #444; background-color: #4eff73; } </style><pre><h1>&#127881;Email sent successfully!</h1></pre>');
} catch (TransportExceptionInterface $e) {
    // Display custom error message
    die('<style>* { font-size: 100px; color: #fff; background-color: #ff4e4e; }</style><pre><h1>&#128544;Error!</h1></pre>');

    // Display real errors
    # echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
}