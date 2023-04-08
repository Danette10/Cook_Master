<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cookorama - Confirmation";
include '../../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';
require_once('../../../vendor/autoload.php');
require_once(PATH_SCRIPT . 'connectDB.php');

$subscriptionType = htmlspecialchars($_GET['subscription']);
$plan = htmlspecialchars($_GET['plan']);
$confirm = htmlspecialchars($_GET['confirm']);

$text = '';

if($confirm == '0'){

    $text = "<p class='text-center'>Une erreur est survenue lors de votre paiement, veuillez réessayer</p>";

}else{

    $text = "<p class='text-justify'>Merci pour votre achat !</p>";
    $text .= "<p class='text-justify'>Vous avez choisi l'abonnement <strong>" . ucfirst($subscriptionType) . "</strong></p>";
    $text .= "<p class='text-justify'>Vous allez recevoir un mail de confirmation de paiement</p>";
    $text .= "<p class='text-justify'>Vous pouvez désormais accéder à votre espace personnel et profiter de fonctionnalités exclusives à votre abonnement !</p>";
    $text .= "<p class='text-justify'>Nous espérons que vous allez apprécier notre site !</p>";
    $text .= "<p class='text-justify'>L'équipe Cookorama</p>";

}

$messageMail = "<h1>Merci pour votre achat !</h1>";
$messageMail .= "<p>Vous avez choisi l'abonnement <strong>" . ucfirst($subscriptionType) . "</strong></p>";
$messageMail .= "<p>Vous pouvez désormais accéder à votre espace personnel et profiter de fonctionnalités exclusives à votre abonnement !</p>";
$messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
$messageMail .= "<p>L'équipe Cookorama</p>";

$subject = "Cookorama - Confirmation de paiement - " . ucfirst($subscriptionType);
$header = "Cookorama < " . MAIL . " >";

mailHtml($_SESSION['email'], $subject, $messageMail, $header);

?>

<body>

    <main>

        <div class="container" style="padding: 40px; font-size: 1.2rem;">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Confirmation</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <?= $text; ?>

                </div>
            </div>
        </div>

    </main>

    <?php
    include PATH_SCRIPT . 'functionsJs.php';
    include PATH_SCRIPT . 'footer.php';
    ?>

</body>
