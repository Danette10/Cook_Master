<?php
include "init.php";
include "functions.php";
include "connectDB.php";

$email = htmlspecialchars($_GET['email']);

$token = bin2hex(random_bytes(64));

$updateUser = $db->prepare("UPDATE user SET token = :token WHERE email = :email");
$updateUser->execute([
    'token' => $token,
    'email' => $email
]);

$messageMail = "<h1>Thank you for your registration !</h1>";
$messageMail .= "<p>Click on the link below to activate your account</p>";
$messageMail .= "<a href='" . ADDRESS_VALIDATE_INSCRIPTION . "?token=" . $token . "'>Activate your account</a>";
$messageMail .= "<p>We hope you will enjoy our services !</p>";
$messageMail .= "<p>Cook Master Team</p>";

$subject = "Cook Master - Activate your account";
$header = "Cook Master < " . MAIL . " >";

mailHtml($email, $subject, $messageMail, $header);

header('Location: ' . ADDRESS_SITE . '?type=success&message=An email has been sent to you to activate your account');
exit();