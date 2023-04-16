<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Pricing";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';
global $db;

$name = [];
$price = [];

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$nameSubscription = '';
$subscriptionPlan = '';
$link = '';
$linkMaster = '';
$linkStarter = '';

if(isset($_SESSION['id'])){

    // Récupérer l'abonnement actuel
    $selectSubscription = $db->prepare('SELECT * FROM stripe_consumer WHERE userId = :id AND subscriptionStatus = :status');
    $selectSubscription->execute(array(
        'id' => $_SESSION['id'],
        'status' => 'active'
    ));

    $subscription = $selectSubscription->fetch();

    if ($subscription) {
        $subscriptionPlan = $subscription['subscriptionPlan'];
        $subscription = \Stripe\Subscription::retrieve($subscription['subscriptionId']);
        $plan = \Stripe\Plan::retrieve($subscriptionPlan);
        $product = \Stripe\Product::retrieve($plan->product);
        $nameSubscription = $product->name;
    }else {
        $nameSubscription = 'Free';
    }

    // Récupérer tout les plans
    $plans = \Stripe\Plan::all([
        'active' => true
    ]);

    // Récupérer les produits
    $products = \Stripe\Product::all([
        'active' => true,
        'type' => 'service'
    ]);


    foreach ($products->data as $product) {

        $name[] = strtolower($product->name);

    }

    $name = array_unique($name);

    foreach ($name as $value) {

        switch ($value) {

            case 'starter':

                $linkStarter = '<a href="' . ADDRESS_SITE . 'subscribe/starter" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>';

                break;

            case 'master':

                $linkMaster = '<a href="' . ADDRESS_SITE . 'subscribe/master" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>';

                break;

            default:

                $link = '<a href="' . ADDRESS_SITE . 'profil/manageSubscription/' . $subscriptionPlan . '/cancel" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>';

                break;

        }
    }

}

// Récupérer les produits
$products = \Stripe\Product::all([
    'active' => true,
    'type' => 'service'
]);
$prices = \Stripe\Price::all([
    'active' => true,
    'type' => 'recurring'
]);

$plans = [];

foreach ($products->data as $product) {
    $name = $product->name;

    foreach ($prices->data as $price_data) {
        
        if ($product->id == $price_data->product) {

            $interval = $price_data->recurring->interval;

            $plans[$name][$interval] = [
                'price' => number_format($price_data->unit_amount / 100, 2, '.', '')
            ];
        }
    }
}

?>

<body>

   <main>

       <h2 class="text-center mt-5">Abonnements</h2>

       <div class="col-md-9 mt-4" style="margin: 0 auto;">

           <table class="table table-bordered border-black">

               <thead>
               <tr>
                   <th scope="col" style="width: 25%;"></th>

                   <th scope="col" style="width: 25%;">

                       <p class="text-center d-flex flex-column align-items-center">
                           <img src="<?= ADDRESS_PRICING_ICON . 'free.png'; ?>" alt="Free" class="img-fluid">
                           <span>Free<?= $nameSubscription == 'Free' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                           <em>Gratuit</em>
                            <?= $nameSubscription != 'Free' ? $link : ''; ?>
                       </p>

                   </th>

                   <th scope="col" style="width: 25%;">

                       <p class="text-center d-flex flex-column align-items-center">
                           <img src="<?= ADDRESS_PRICING_ICON . 'starter.png'; ?>" alt="Starter" class="img-fluid">
                           <span>Starter<?= $nameSubscription == 'Starter' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                           <em><?= $plans['Starter']['month']['price']; ?> €/mois ou <?= $plans['Starter']['year']['price']; ?> €/an</em>
                           <?= $nameSubscription != 'Starter' ? $linkStarter : ''; ?>
                       </p>

                   </th>

                   <th scope="col" style="width: 25%;">

                       <p class="text-center d-flex flex-column align-items-center">
                           <img src="<?= ADDRESS_PRICING_ICON . 'master.png'; ?>" alt="Master" class="img-fluid">
                           <span>Master<?= $nameSubscription == 'Master' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                            <em><?= $plans['Master']['month']['price']; ?> €/mois ou <?= $plans['Master']['year']['price']; ?> €/an</em>
                            <?= $nameSubscription != 'Master' ? $linkMaster : ''; ?>
                       </p>

                   </th>

               </tr>
               </thead>

               <tbody style="vertical-align: middle;">

               <tr>
                   <th scope="row">Présence de publicités dans le contenu</th>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Commenter, publier des avis</th>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Accès aux leçons</th>
                   <td class="text-center">1 par jour</td>
                   <td class="text-center">5 par jour</td>
                   <td class="text-center">illimité</td>
               </tr>

               <tr>
                   <th scope="row">Accès au service de tchat avec un chef</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Réduction permanente de 5% sur la boutique</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Livraison offerte sur la boutique</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i><br>Uniquement en point relai</td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Accès au service de location d’espace de cuisine</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Invitation à des événements exclusifs (dégustations, rencontres avec des chefs, ventes privées…)</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i></td>
               </tr>

               <tr>
                   <th scope="row">Récompense cooptation nouvel inscrit</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i><br>Chèque cadeau de 5€ tous les 3 nouveaux inscrits<strong>*</strong></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i><br>
                       Chèque cadeau de 5€ pour chaque nouvel inscrit<strong>*</strong> + bonus de 3% du montant sur le total de la première commande du nouvel inscrit
                   </td>
               </tr>

               <tr>
                   <th scope="row">Bonus renouvellement de l’abonnement</th>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-sharp fa-solid fa-circle-xmark fa-2xl" style="color: #eb0000;"></i></td>
                   <td class="text-center"><i class="fa-solid fa-circle-check fa-2xl" style="color: #00f05c;"></i><br>
                       Réduction de 10% du montant de l’abonnement en cas de renouvellement, valable uniquement sur le tarif annuel
                   </td>
               </tr>

               </tbody>

           </table>

           <p><strong>*</strong> Hors formule <strong><em>Free</em></strong><span><img src="<?= ADDRESS_PRICING_ICON . 'free.png'; ?>" alt="Free" width="50"></span></p>

       </div>

   </main>

</body>