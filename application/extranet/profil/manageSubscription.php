<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Gestion des abonnements";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}

// Récupérer l'abonnement actuel
$selectSubscription = $db->prepare('SELECT * FROM stripe_consumer WHERE userId = :id AND subscriptionStatus = :status');
$selectSubscription->execute(array(
    'id' => $_SESSION['id'],
    'status' => 'active'
));

$subscription = $selectSubscription->fetch();

if(!$subscription){
    header('Location: ' . ADDRESS_SITE . 'profil');
    exit();
}
ob_end_flush();

// abonnement actuel
$subscriptionPlan = $subscription['subscriptionPlan'];
\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$subscription = \Stripe\Subscription::retrieve($subscription['subscriptionId']);
$plan = \Stripe\Plan::retrieve($subscriptionPlan);
$product = \Stripe\Product::retrieve($plan->product);
$nameSubscription = $product->name;

?>

<body>

    <main>

        <h2 class="text-center mt-4">Votre abonnement actuel : <strong><?= $nameSubscription ?></strong></h2>

        <div>

            <h4 class="m-5">Votre abonnement expire le : <strong><?= date('d/m/Y', $subscription->current_period_end) ?></strong></h4>

            <a class="btn btn-danger ms-5" href="<?= ADDRESS_SITE ?>profil/manageSubscription/<?= $subscriptionPlan ?>/cancel" onclick="confirm('Voulez-vous vraiment annuler votre abonnement ?')?null:event.preventDefault()">Annuler l'abonnement</a>

            <a class="btn btn-primary ms-2" href="<?= ADDRESS_SITE ?>subscribe">Changer d'abonnement</a>

        </div>

    </main>

</body>
