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
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['lastname'];
    $_SESSION['firstname'] = $user['firstname'];

    header('Location: ' . ADDRESS_SITE . '?type=success&message=Votre compte a bien été validé');
    exit();
} else {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de la validation de votre compte');
    exit();
}
