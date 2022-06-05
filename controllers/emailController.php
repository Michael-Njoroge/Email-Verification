<?php

require_once 'vendor/autoload.php';
require_once 'config/constants.php';

//create the transport2
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl')) //email server responsible for receiving and sending emails

    ->setPassword(PASSWORD)
    ->setUsername(EMAIL);



//create the mailer using your created transport3
$mailer = new Swift_Mailer($transport);



function sendVerificationEmail($userEmail, $token)
{
    global $mailer;
    $body = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email</title>
    </head>
    <body>
        <div class="wrapper"> //wrap our message inside 
            <p>
               Thank you for signing up on our website.Please click on the link below to verify your email 
            </p>
            <a href="http://localhost/hello/index.php?token= ' . $token . '">
                Verify your email address
            </a>
        </div>
        
    </body>
    </html>';


    //create a message
    $message = (new Swift_Message('Verify your email address'))
        ->setFrom(EMAIL)
        ->setTo($userEmail)
        ->setBody($body, 'text/html');

    //send the message
    $result = $mailer->send($message);
}
