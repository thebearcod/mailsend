<?php

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$data = [
    'name' => $_POST['data']['name'],
    'phone' => $_POST['data']['phone'],
    'email' => $_POST['data']['email'],
    'utm' => $_POST['data']['utm'],
];

// сформируем шаблон письма и вставим значение с формы
$body = file_get_contents('email.tpl.php');
foreach ($data as $key => $value) {
    $body = str_replace('{' . $key . '}', $value, $body);
}

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.yandex.ru';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'address@example.com';                     //SMTP username
    $mail->Password = 'password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->CharSet = "utf-8";

    //Recipients
    $mail->setFrom('address@example.com', 'WikiWorks');
    $mail->addAddress('hello@example.com', 'WikiWorks');     //Add a recipient hello@example.com
    $mail->addReplyTo('address@example.com', 'WikiWorks');
    $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/img/logo-wikiworks.png','logo-wikiworks.png','logo-wikiworks.png');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'WikiWorks: Запрос на демонстрацию';
    $mail->Body = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    $status = 'Message has been sent';
} catch (Exception $e) {
    $status = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

exit(json_encode(['status' => $status]));
