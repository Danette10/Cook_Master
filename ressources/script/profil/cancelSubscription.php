<?php
ob_start();
include "ressources/script/head.php";
global $db;
\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$selectSubscription = $db->prepare('SELECT * FROM stripe_consumer WHERE idUser = :id AND subscriptionStatus = :status');
$selectSubscription->execute(array(
    'id' => $_SESSION['id'],
    'status' => 'active'
));

$subscription = $selectSubscription->fetch();

$subscription = \Stripe\Subscription::retrieve($subscription['subscriptionId']);
$subscription->cancel();

$updateSubscription = $db->prepare('UPDATE stripe_consumer, users SET subscriptionStatus = :status, role = 1 WHERE stripe_consumer.idUser = :idUser AND users.idUser = :idUser');
$updateSubscription->execute(array(
    'status' => 'canceled',
    'idUser' => $_SESSION['id']
));

$mailHTML = '<h1>Annulation d\'abonnement</h1>
<p>Bonjour ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . ',</p>
<p>Votre abonnement a bien été annulé.</p>
<p>Vous pouvez à tout moment vous réabonner sur notre site.</p>
<p>En espérant pouvoir vous compter parmi nos abonnés à nouveau 😊</p>
<p>Cordialement,</p>
<p>L\'équipe Cookorama</p>';

$subject = "Cookorama - Annulation d'abonnement";
$header = "Cookorama < " . MAIL . " >";

mailHtml($_SESSION['email'], $subject, $mailHTML, $header);

redirectUser();

ob_end_flush();

?>
