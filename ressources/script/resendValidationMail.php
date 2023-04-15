<?php
include "init.php";
include "functions.php";

global $db;

$email = htmlspecialchars($_GET['email']);

$token = bin2hex(random_bytes(64));

$updateUser = $db->prepare("UPDATE user SET token = :token WHERE email = :email");
$updateUser->execute([
    'token' => $token,
    'email' => $email
]);

$messageMail = "<h1>Merci pour votre inscription !</h1>";
$messageMail .= "<p>Vous pouvez activer votre compte en cliquant sur le lien ci-dessous</p>";
$messageMail .= "<a href='" . ADDRESS_VALIDATE_INSCRIPTION . "?token=" . $token . "'>Activer mon compte</a>";
$messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
$messageMail .= "<p>L'équipe Cookorama</p>";

$subject = "Cookorama - Activation de votre compte";
$header = "Cookorama < " . MAIL . " >";

mailHtml($email, $subject, $messageMail, $header);

header('Location: ' . ADDRESS_SITE . '?type=success&message=Un mail de validation vient de vous être envoyé');
exit();