<?php
session_start();
include '../init.php';
include PATH_SCRIPT . 'functions.php';
include PATH_SCRIPT . 'connectDB.php';

$token = htmlspecialchars($_GET['token']);

$selectUser = $db->prepare("SELECT * FROM user WHERE token = :token");
$selectUser->execute(['token' => $token]);
$user = $selectUser->fetch();

if($user) {
    $updateUser = $db->prepare("UPDATE user SET token = NULL, role = 1 WHERE token = :token");
    $updateUser->execute(['token' => $token]);

    $messageMail = "<p>Bonjour,</p>";
    $messageMail .= "<p>Nous vous confirmons que votre compte a bien été validé.</p>";
    $messageMail .= "<p>Vous pouvez dès à présent vous connecter à votre compte.</p>";
    $messageMail .= "<p>Nous espérons que vous apprécierez notre service. Nous vous souhaitons une bonne journée.</p>";
    $messageMail .= "<p>L'équipe Cook Master</p>";

    $subject = "Cook Master - Confirmation d'inscription";
    $header = "Cook Master < " . MAIL . " >";

    mailHtml($user['email'], $subject, $messageMail, $header);

    header('Location: ' . ADDRESS_SITE . '?type=success&message=Votre compte a bien été validé');
    exit();
} else {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de la validation de votre compte');
    exit();
}
