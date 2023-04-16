<?php
ob_start();
include "ressources/script/head.php";
global $db;
\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$selectSubscription = $db->prepare('SELECT * FROM stripe_consumer WHERE userId = :id AND subscriptionStatus = :status AND subscriptionPlan = :plan');
$selectSubscription->execute(array(
    'id' => $_SESSION['id'],
    'status' => 'active',
    'plan' => $subscriptionType
));

$subscription = $selectSubscription->fetch();

$subscription = \Stripe\Subscription::retrieve($subscription['subscriptionId']);
$subscription->cancel();

$updateSubscription = $db->prepare('UPDATE stripe_consumer, user SET subscriptionStatus = :status, role = 1 WHERE stripe_consumer.userId = :id AND user.id = :id');
$updateSubscription->execute(array(
    'status' => 'canceled',
    'id' => $_SESSION['id']
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

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
ob_end_flush();

?>
