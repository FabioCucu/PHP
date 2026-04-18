<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__.'/vendor/autoload.php';
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = "fabio.cucu@iisviolamarchesini.edu.it";
    $mail->Password   = 'oohw qajk qdzu dzjc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail-> setFrom("fabio.cucu@iisviolamarchesini.edu.it"); // Origine del messaggio
    $mail->addAddress("marcello.targa@iisviolamarchesini.edu.it"); // Destinatario del messaggio
    $mail->Subject="Test email con PhpMailer";
    $mail->Body="Salve, mi chiamo Fabio e ti scrivo per proporti un lavoro.
    Offro un impiego part-time che permette di ottenere tra i 30 e i 300 euro all'ora, senza spese, nel giro di 1 ora.
    Per far ciò è sufficente cliccare sul link sottostante, seguire le istruzioni e mandare i tuoi dati bancari per la consegna dei soldi direttamente all'interno del tuo conto!
    www.sendmymoney/myMomIsKindaHomeless.com";
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->send();
    echo 'Message has been sent';
}  catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}