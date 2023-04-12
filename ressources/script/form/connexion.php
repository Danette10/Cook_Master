<?php
include '../init.php';
include PATH_SCRIPT . 'functions.php';
include PATH_SCRIPT . 'connectDB.php';

session_start();

// Limiter le taux de requêtes
$ip_address = $_SERVER['REMOTE_ADDR'];
$attempts_limit = 5;
$time_limit_seconds = 60; // 1 minute

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = array();
}

if (isset($_SESSION['login_attempts'][$ip_address])) {
    $attempts_data = $_SESSION['login_attempts'][$ip_address];
    $time_elapsed = time() - $attempts_data['timestamp'];

    if ($time_elapsed > $time_limit_seconds) {
        // Réinitialiser le compteur d'essais et le timestamp
        $_SESSION['login_attempts'][$ip_address] = array('count' => 1, 'timestamp' => time());
    } else {
        if ($attempts_data['count'] >= $attempts_limit) {
            // Trop d'essais de connexion, redirection vers une page d'erreur
            header('Location: ' . ADDRESS_SITE . '?type=error&message=Trop de tentatives de connexion, veuillez patienter');
            exit();
        } else {
            // Incrémenter le compteur d'essais
            $_SESSION['login_attempts'][$ip_address]['count']++;
        }
    }
} else {
    // Ajouter l'adresse IP au compteur d'essais
    $_SESSION['login_attempts'][$ip_address] = array('count' => 1, 'timestamp' => time());
}

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if (empty($email) || empty($password)) {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Veuillez remplir tous les champs');
    exit();
}

$selectIfUserExist = $db->prepare('SELECT id, lastname, firstname, role, profilePicture, COUNT(*) AS userExist FROM user WHERE email = :email');
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

$_SESSION['id'] = $userExist['id'];
$_SESSION['lastname'] = $userExist['lastname'];
$_SESSION['firstname'] = $userExist['firstname'];
$_SESSION['email'] = $email;
$_SESSION['role'] = $userExist['role'];

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

// Réinitialiser le compteur d'essais pour cette IP après une connexion réussie
unset($_SESSION['login_attempts'][$ip_address]);

header('Location: ' . ADDRESS_SITE . '?type=success&message=Connexion réussie');
exit();
?>