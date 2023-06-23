<?php
session_start();
include '../init.php';
include PATH_SCRIPT . 'functions.php';

global $db;

// Limiter le taux de requêtes
$ip_address = $_SERVER['REMOTE_ADDR'];
$attempts_limit = 5;
$time_limit_seconds = 300; // 5 minutes
$error = '';

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if (empty($email) || empty($password)) {
    $error .= '<p class="text-danger">Veuillez remplir tous les champs</p>';
}

$selectIfUserExist = $db->prepare('SELECT COUNT(*) AS userExist FROM users WHERE email = :email');
$selectIfUserExist->execute(array(
    'email' => $email
));
$userExist = $selectIfUserExist->fetch();

if ($userExist['userExist'] == 0) {
    echo '<p class="text-danger">Email ou mot de passe incorrect</p>';
}

$selectIfUserExist = $db->prepare('SELECT idUser, lastname, firstname, password, role, profilePicture FROM users WHERE email = :email GROUP BY idUser');
$selectIfUserExist->execute(array(
    'email' => $email
));
$userExist = $selectIfUserExist->fetch();
$resultCount = $selectIfUserExist->rowCount();
if ($resultCount == 1 && $userExist['role'] == 0) {

    $error .= '<p class="text-danger">Vous n\'avez pas encore validé votre compte.</p>';

}

if ($resultCount == 1 && $userExist['role'] == -2) {

    $error .= '<p class="text-danger">Vous avez été banni.</p>';

}

$password = hash('sha512', $password);
$selectIfPasswordIsCorrect = $db->prepare('SELECT COUNT(*) AS passwordIsCorrect FROM users WHERE email = :email AND password = :password');
$selectIfPasswordIsCorrect->execute(array(
    'email' => $email,
    'password' => $password
));
$passwordIsCorrect = $selectIfPasswordIsCorrect->fetch();

if ($passwordIsCorrect['passwordIsCorrect'] == 0) {
    $error .= '<p class="text-danger">Mot de passe incorrect</p>';
}

if(!empty($error)){

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
                // Bloquer l'adresse IP
                $error .= '<p class="text-danger">Vous avez dépassé le nombre d\'essais autorisés. Veuillez réessayer dans ' . ($time_limit_seconds - $time_elapsed) . ' secondes.</p>';
            } else {
                // Incrémenter le compteur d'essais
                $_SESSION['login_attempts'][$ip_address]['count']++;
                $error .= '<p class="text-danger">Essai ' . $_SESSION['login_attempts'][$ip_address]['count'] . ' sur ' . $attempts_limit . '</p>';
            }
        }
    } else {
        // Ajouter l'adresse IP au compteur d'essais
        $_SESSION['login_attempts'][$ip_address] = array('count' => 1, 'timestamp' => time());
    }

    echo $error;

    $file = PATH_SITE. 'log/connexion_error.txt';
    $message = 'Connexion échouée de ' . $email . ' le ' . date('d/m/Y à H:i:s');

    writeLog($file, $message);

}else{

    $_SESSION['id'] = $userExist['idUser'];
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

    $file = PATH_SITE. 'log/connexion_success.txt';
    $message = 'Connexion réussie de ' . $email . ' le ' . date('d/m/Y à H:i:s');

    writeLog($file, $message);

    echo 'success';
}


?>