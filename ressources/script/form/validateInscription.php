<?php
include PATH_SCRIPT . 'functions.php';

global $db;

$selectUser = $db->prepare("SELECT * FROM users WHERE token = :token AND role = 0");
$selectUser->execute(['token' => $token]);
$user = $selectUser->fetch();

if($typeInscription == '2'){
    $role = -1;
}else{
    $role = 1;
}

if($user) {

    $updateUser = $db->prepare("UPDATE users SET token = '', role = :role WHERE token = :token");
    $updateUser->execute([
        'role' => $role,
        'token' => $token
    ]);

    $messageMail = "<p>Bonjour,</p>";
    $messageMail .= "<p>Nous vous confirmons que votre compte a bien été validé.</p>";
    $messageMail .= "<p>Vous pouvez dès à présent vous connecter à votre compte.</p>";
    if($typeInscription == '2'){
        $messageMail .= "<p><strong>Vous aurez accès à votre compte professionnel d'ici quelques jours.</strong></p>";
    }
    $messageMail .= "<p>Nous espérons que vous apprécierez notre service. Nous vous souhaitons une bonne journée.</p>";
    $messageMail .= "<p>L'équipe Cookorama</p>";

    $subject = "Cookorama - Confirmation d'inscription";
    $header = "Cookorama < " . MAIL . " >";

    mailHtml($user['email'], $subject, $messageMail, $header);

    if($typeInscription == '2') {
        header('Location: ' . ADDRESS_SITE . '?type=success&message=Votre compte a bien été validé, néanmoins vous n\'avez pas encore accès à votre compte professionnel. Celui-ci sera validé dans les prochains jours.');
        exit();
    }else{
        header('Location: ' . ADDRESS_SITE . '?type=success&message=Votre compte a bien été validé.');
        exit();
    }
} else {
    header('Location: ' . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de la validation de votre compte');
    exit();
}
