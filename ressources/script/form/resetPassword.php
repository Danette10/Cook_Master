<?php
include "../init.php";
include PATH_SCRIPT . "functions.php";
include PATH_SCRIPT . "connectDB.php";

$token = htmlspecialchars($_POST['token']);
$email = htmlspecialchars($_POST['email']);

if($token == ''){

    $token = bin2hex(random_bytes(64));
    $updateToken = $db->prepare("UPDATE user SET token = :token WHERE email = :email");
    $updateToken->execute(['token' => $token, 'email' => $email]);

    $messageMail = "<p>Bonjour,</p>";
    $messageMail .= "<p>Vous venez de demander à réinitialiser votre mot de passe sur Cookorama.</p>";
    $messageMail .= "<p>Pour ce faire, veuillez cliquer sur le lien ci-dessous :</p>";
    $messageMail .= "<a href='" . ADDRESS_RESET_PASSWORD . "?token=" . $token . "'>Réinitialiser mon mot de passe</a>";
    $messageMail .= "<p>L'équipe Cookorama</p>";

    $subject = "Cookorama - Réinitialisation de votre mot de passe";
    $header = "Cookorama < " . MAIL . " >";

    mailHtml($email, $subject, $messageMail, $header);

    header("Location: " . ADDRESS_SITE . '?type=success&message=Un email vous a été envoyé pour réinitialiser votre mot de passe');


}else{

    $password = htmlspecialchars($_POST['passwordReset']);
    $passwordConfirm = htmlspecialchars($_POST['passwordConfReset']);

    $errors = [];

    if($password == ''){
        $errors[] = "Veuillez renseigner un mot de passe";
    }

    if($password != $passwordConfirm){
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header("Location: " . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de la réinitialisation de votre mot de passe');
        exit();
    }

    $password = hash('sha512', $password);

    $updatePassword = $db->prepare("UPDATE user SET password = :password WHERE token = :token");
    $updatePassword->execute(['password' => $password, 'token' => $token]);

    $messageMail = "<p>Bonjour,</p>";
    $messageMail .= "<p>Nous vous confirmons que votre mot de passe a bien été réinitialisé.</p>";
    $messageMail .= "<p>L'équipe Cookorama</p>";

    $subject = "Cookorama - Confirmation de réinitialisation de votre mot de passe";
    $header = "Cookorama < " . MAIL . " >";

    mailHtml($email, $subject, $messageMail, $header);

    header("Location: " . ADDRESS_SITE . '?type=success&message=Votre mot de passe a bien été réinitialisé');
    exit();

}