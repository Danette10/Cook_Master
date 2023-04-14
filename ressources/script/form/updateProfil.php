<?php
session_start();
include '../init.php';
include PATH_SCRIPT . 'functions.php';

$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
$newPassword = isset($_POST['newPassword']) ? htmlspecialchars($_POST['newPassword']) : '';
$confirmNewPassword = isset($_POST['confirmNewPassword']) ? htmlspecialchars($_POST['confirmNewPassword']) : '';
$lastname = isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
$firstname = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '';
$birthdate = isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : '';
$profilePicture = "";


if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}

if(!empty($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0){

    $file = uploadProfilePicture($_FILES['profilePicture']);

    if(is_int($file)){
        $errors[] = "Erreur lors de l'upload de l'image";
    }else{
        $profilePicture = $file;
    }

    $updateUser = $db->prepare("UPDATE user SET profilePicture = :profilePicture WHERE id = :id");
    $updateUser->execute(
        [
        'profilePicture' => $profilePicture,
        'id' => $_SESSION['id']
        ]
    );
}

if(!empty($email)){

    $selectUser = $db->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
    $selectUser->execute(['email' => $email]);
    $user = $selectUser->fetchColumn();

    if ($user > 0) {
        $errors[] = "L'adresse email est déjà utilisée";
    }else {

        $token = bin2hex(random_bytes(64));
        $updateUser = $db->prepare("UPDATE user SET email = :email, token = :token, role = :role WHERE id = :id");
        $updateUser->execute(
            [
                'email' => $email,
                'token' => $token,
                'role' => 0,
            ]
        );

        $messageMail = "<h1>Merci pour votre inscription !</h1>";
        $messageMail .= "<p>Vous pouvez activer votre compte en cliquant sur le lien ci-dessous</p>";
        $messageMail .= "<a href='" . ADDRESS_VALIDATE_INSCRIPTION . "?token=" . $token . "'>Activer mon compte</a>";
        $messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
        $messageMail .= "<p>L'équipe Cookorama</p>";

        $subject = "Cookorama - Activation de votre compte";
        $header = "Cookorama < " . MAIL . " >";

        mailHtml($email, $subject, $messageMail, $header);
    }
}

if($newPassword != $confirmNewPassword){
    $errors[] = "Les mots de passe ne correspondent pas";
}

if( preg_match("#\d#",$newPassword)== 0 ||
    preg_match("#[a-z]#",$newPassword)== 0 ||
    preg_match("#[A-Z]#",$newPassword)== 0 ||
    strlen($newPassword) < 8
) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre";
}

if(!empty($newPassword) && !empty($confirmNewPassword)){

    $password = hash('sha512', $password);
    $newPassword = hash('sha512', $newPassword);

    $selectUser = $db->prepare("SELECT COUNT(*) FROM user WHERE password = :password");
    $selectUser->execute(['password' => $password]);
    $user = $selectUser->fetchColumn();

    if ($user == 0) {
        $errors[] = "Le mot de passe est incorrect";
    }else {

        $updateUser = $db->prepare("UPDATE user SET password = :password WHERE id = :id");
        $updateUser->execute(
            [
                'password' => $newPassword,
                'id' => $_SESSION['id']
            ]
        );

        $messageMail = "<h1>Modification de votre mot de passe</h1>";
        $messageMail .= "<p>Vous avez modifié votre mot de passe sur Cookorama</p>";
        $messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
        $messageMail .= "<p>L'équipe Cookorama</p>";

        $subject = "Cookorama - Modification de votre mot de passe";
        $header = "Cookorama < " . MAIL . " >";

        mailHtml($email, $subject, $messageMail, $header);
    }
}

if(!empty($lastname)){

    $updateUser = $db->prepare("UPDATE user SET lastname = :lastname WHERE id = :id");
    $updateUser->execute(
        [
            'lastname' => $lastname,
            'id' => $_SESSION['id']
        ]
    );
}

if(!empty($firstname)){

    $updateUser = $db->prepare("UPDATE user SET firstname = :firstname WHERE id = :id");
    $updateUser->execute(
        [
            'firstname' => $firstname,
            'id' => $_SESSION['id']
        ]
    );
}

if(!empty($birthdate)){

    $updateUser = $db->prepare("UPDATE user SET birthdate = :birthdate WHERE id = :id");
    $updateUser->execute(
        [
            'birthdate' => $birthdate,
            'id' => $_SESSION['id']
        ]
    );
}

if (isset($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ' . ADDRESS_SITE . 'profil');
    exit();
}

header('Location: ' . ADDRESS_SITE . 'profil');
exit();