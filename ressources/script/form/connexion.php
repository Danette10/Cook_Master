<?php
include '../init.php';
include PATH_SCRIPT . 'functions.php';
include PATH_SCRIPT . 'connectDB.php';

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if (empty($email) || empty($password)) {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Veuillez remplir tous les champs');
    exit();
}

$selectIfUserExist = $db->prepare('SELECT lastname, firstname, role, profilePicture, COUNT(*) AS userExist FROM user WHERE email = :email');
$selectIfUserExist->execute(array(
    'email' => $email
));
$userExist = $selectIfUserExist->fetch();

if ($userExist['userExist'] == 0) {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Email ou mot de passe incorrect');
    exit();
}

if($userExist['role'] == 0){
    $message = 'Vous n\'avez pas validé votre compte.';
    header('Location: ' . ADDRESS_SITE . '?type=error&message=' . $message . '&email=' . $email);
    exit();
}

$password = hash('sha512', $password);

$selectIfPasswordIsCorrect = $db->prepare('SELECT COUNT(*) AS passwordIsCorrect FROM user WHERE email = :email AND password = :password');
$selectIfPasswordIsCorrect->execute(array(
    'email' => $email,
    'password' => $password
));
$passwordIsCorrect = $selectIfPasswordIsCorrect->fetch();

if ($passwordIsCorrect['passwordIsCorrect'] == 0) {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Mot de passe incorrect');
    exit();
}

session_start();

$_SESSION['lastname'] = $userExist['lastname'];
$_SESSION['firstname'] = $userExist['firstname'];
$_SESSION['email'] = $email;
$_SESSION['role'] = $userExist['role'];
$_SESSION['profilePicture'] = $userExist['profilePicture'];

switch ($userExist['role']) {
    case 1:
        $_SESSION['subscriptionType'] = 'Free';
        break;
    case 2:
        $_SESSION['subscriptionType'] = 'Starter';
        break;
    case 3:
        $_SESSION['subscriptionType'] = 'Master';
        break;
}

header('Location: ' . ADDRESS_SITE . '?type=success&message=Connexion réussie');
exit();