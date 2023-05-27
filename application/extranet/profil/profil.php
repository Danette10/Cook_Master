<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Profil";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}


$userId = $_SESSION['id'];

$selectInfo = $db->prepare('SELECT * FROM users WHERE users.idUser = :idUser');
$selectInfo->execute(array(
    'idUser' => $userId
));

$infos = $selectInfo->fetch();

$lastname = $infos['lastname'];
$firstname = $infos['firstname'];
$email = $infos['email'];
$role = $infos['role'];
$fidelityCounter = $infos['fidelityCounter'];
$creation = date('d/m/Y', strtotime($infos['creation']));
$birthdate = date('d/m/Y', strtotime($infos['birthdate']));
$profilePicture = ADDRESS_IMG_PROFIL . $infos['profilePicture'];

$selectCountSub = $db->prepare('SELECT COUNT(*) AS countSub FROM stripe_consumer WHERE idUser = :idUser');
$selectCountSub->execute(array(
    'idUser' => $userId
));

$countSub = $selectCountSub->fetch();

if($countSub['countSub'] > 0){
    $selectSubscription = $db->prepare('SELECT subscriptionStatus FROM stripe_consumer WHERE idUser = :idUser');
    $selectSubscription->execute(array(
        'idUser' => $userId
    ));

    $subscription = $selectSubscription->fetchAll(PDO::FETCH_ASSOC);

    foreach($subscription as $sub){
        $subscribed = $sub;
    }

}else{
    $subscribed = null;
}

?>

    <body>

        <main>

            <h2 class="mt-3 text-center">Bienvenue sur votre profil <?= $firstname ?> !</h2>

            <div class="allInfoProfil">

                <div class="imageProfil me-4">

                    <?php if($profilePicture != ADDRESS_IMG_PROFIL){ ?>
                    <img src="<?= $profilePicture ?>" alt="Photo de profil" width="300" height="300">
                    <?php } else { ?>
                    <img src="<?= ADDRESS_DEFAULT_PROFIL ?>" alt="Photo de profil" width="300" height="300">
                    <?php } ?>

                    <div class="editProfil">

                        <a href="<?= ADDRESS_SITE ?>profil/modify/<?= $userId ?>" data-bs-toggle="tooltip" data-bs-title="Modifier le profil" data-bs-placement="top">

                            <i class="fa-solid fa-pen-to-square fa-2xl" style="color: #ff9b90;"></i>

                        </a>

                    </div>
                </div>

                <div class="infoProfil">
                    <p><strong>Nom : </strong><?= $lastname ?></p>
                    <p><strong>Prénom : </strong><?= $firstname ?></p>
                    <p><strong>Email : </strong><?= $email ?></p>
                    <p><strong>Date de naissance : </strong><?= $birthdate ?></p>
                    <p><strong>Date d'inscription : </strong><?= $creation ?></p>
                    <p><strong>Nombre de points de fidélité : </strong><?= $fidelityCounter ?></p>
                    <?php
                    if($role == -1){
                    ?>
                        <p><strong>Compte préstataire : </strong><em>En cours de validation</em></p>
                    <?php }else if($role == 4){ ?>
                        <p><strong>Compte préstataire : </strong>Validé</p>
                    <?php }else if($role == 5){ ?>
                        <p style="color: #FF9B90;"><strong>Administrateur</strong></p>
                    <?php } ?>
                    <?php
                    if($subscribed !== null && $subscribed['subscriptionStatus'] == 'active'){
                    ?>
                        <button class="manageSubLink btn">
                            <a href="<?= ADDRESS_SITE ?>profil/manage/subscription" class="nav-link">Gérer votre abonnement</a>
                        </button>
                    <?php
                    }else if($role != 5){
                    ?>
                        <button class="manageSubLink btn">
                            <a href="<?= ADDRESS_SITE ?>subscribe" class="nav-link">S'abonner</a>
                        </button>
                    <?php
                    }
                    if($role != 5){
                    ?>
                    <button class="manageInvoice btn">
                        <a href="<?= ADDRESS_SITE ?>profil/manage/invoice" class="nav-link">Voir mes factures</a>
                    </button>
                    <?php
                    }
                    ?>
                </div>

            </div>

        </main>

    </body>

</html>
