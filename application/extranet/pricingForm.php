<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Pricing";
include '../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';

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
                           <span>Free<?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] == 'Free' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                           <em>Gratuit</em>
                            <?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] != 'Free' ? '<a href="' . ADDRESS_SITE . 'subscribe/free" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>' : ''; ?>
                       </p>

                   </th>

                   <th scope="col" style="width: 25%;">

                       <p class="text-center d-flex flex-column align-items-center">
                           <img src="<?= ADDRESS_PRICING_ICON . 'starter.png'; ?>" alt="Starter" class="img-fluid">
                           <span>Starter<?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] == 'Starter' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                           <em>9,90€ / mois ou 113€/an</em>
                           <?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] != 'Starter' ? '<a href="' . ADDRESS_SITE . 'subscribe/starter" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>' : ''; ?>
                       </p>

                   </th>

                   <th scope="col" style="width: 25%;">

                       <p class="text-center d-flex flex-column align-items-center">
                           <img src="<?= ADDRESS_PRICING_ICON . 'master.png'; ?>" alt="Master" class="img-fluid">
                           <span>Master<?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] == 'Master' ? '<span class="badge text-bg-success ms-2">Abonnement actuel</span>' : ''; ?></span>
                       </p>

                       <p style="font-size: 13px;">
                           <em>19€ / mois ou 220€ / an</em>
                            <?= isset($_SESSION['subscriptionType']) && $_SESSION['subscriptionType'] != 'Master' ? '<a href="' . ADDRESS_SITE . 'subscribe/master" class="btn ms-1" id="choosePlan">Choisir cet abonnement</a>' : ''; ?>
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

    <?php
    include PATH_SCRIPT . 'functionsJs.php';
    include PATH_SCRIPT . 'footer.php';
    ?>

</body>