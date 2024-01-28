<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

try {

    //Server settings
    $mail->isSMTP();
  	$mail->Host = $_ENV['SMTP_HOST'];
  	$mail->SMTPAuth = true;
  	$mail->Username = $_ENV['SMTP_USERNAME'];
  	$mail->Password = $_ENV['SMTP_PASSWORD'];
  	$mail->SMTPSecure = 'tls';
  	$mail->Port = $_ENV['SMTP_PORT'];	
    
    //Recipients - main edits
    $mail->setFrom($_ENV['FROM_EMAIL'], $_ENV['FROM_NAME']);             // Email Address and Name FROM
    $mail->addAddress($_ENV['TO_EMAIL'], $_ENV['TO_NAME']);                  // Email Address and Name TO - Name is optional
    $mail->addReplyTo($_ENV['REPLYTO_EMAIL'], $_ENV['REPLYTO_NAME']);       // Email Address and Name NOREPLY
    $mail->isHTML(true);                                                       
    $mail->Subject = "Message from Kipaki website";                                // Email Subject     
   
   // Email verification, do not edit
    function isEmail($email_booking ) {
        return(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$email_booking ));
    }

    // Form fields
    $date_booking     = $_POST['date_booking'];
    $rooms_booking     = $_POST['rooms_booking'];
    $adults_booking     = $_POST['adults_booking'];
    $childs_booking     = $_POST['childs_booking'];
    $name_booking        = $_POST['name_booking'];
    $email_booking    = $_POST['email_booking'];
    $verify_booking = $_POST['verify_booking'];
    $phone_contact    = $_POST['phone_contact'];

    if(trim($date_booking) == '') {
        echo '<div class="error_message">Please enter your dates.</div>';
        exit();
    } else if(trim($adults_booking ) == '') {
        echo '<div class="error_message">Please enter number of adults.</div>';
        exit();
    } else if(trim($childs_booking ) == '') {
        echo '<div class="error_message">Please enter number of childs.</div>';
        exit();
    } else if(trim($name_booking ) == '') {
        echo '<div class="error_message">Please enter your Name.</div>';
        exit();
    } else if(trim($email_booking) == '') {
        echo '<div class="error_message">Please enter a valid email address.</div>';
        exit();
    } else if(!isEmail($email_booking)) {
        echo '<div class="error_message">Invalid e-mail address, try again.</div>';
        exit();
    } else if(!isset($verify_booking) || trim($verify_booking) == '') {
        echo '<div class="error_message"> Please enter the verification number.</div>';
        exit();
    } else if(trim($verify_booking) != '4') {
        echo '<div class="error_message">The verification number you entered is incorrect.</div>';
        exit();
    } else if(trim($phone_contact) == '') {
      echo '<div class="error_message">Please enter a valid phone number.</div>';
      exit();
    } 

    // Get the email's html content
    $email_html = file_get_contents('template-email.html');

    // Setup html content
    // Setup html content
     $e_content = "You have been contacted by <strong>$name_booking</strong> with the following booking request:<br><br>Check in / out: $date_booking<br><br>Number of adults: $adults_booking<br><br>Number of children: $childs_booking <br><br>You can contact $name_booking via email at $email_booking or by phone at $phone_contact";
    $body = str_replace(array('message'),array($e_content),$email_html);
    $mail->MsgHTML($body);

    $mail->send();

    // Confirmation/autoreplay email send to who fill the form
    $mail->ClearAddresses();
    $mail->isSMTP();
    $mail->addAddress($_POST['email_booking']); // Email address entered on form
    $mail->isHTML(true);
    $mail->Subject    = 'Confirmation'; // Custom subject
    
    // Get the email's html content
    $email_html_confirm = file_get_contents('confirmation.html');

    // Setup html content
    $body = str_replace(array('message'),array($e_content),$email_html_confirm);
    $mail->MsgHTML($body);

    $mail->Send();

     // Succes message
    echo '<div id="success_page">
            <div class="icon icon--order-success svg">
                 <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                  <g fill="none" stroke="#8EC343" stroke-width="2">
                     <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                     <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                  </g>
                 </svg>
             </div>
            <h5>Thank you!<span>Request successfully sent!</span></h5>
        </div>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }   
?> 