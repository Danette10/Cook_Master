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

$selectInfo = $db->prepare('SELECT * FROM users WHERE users.idUser = :idUser');
$selectInfo->execute(array(
    'idUser' => $_SESSION['id']
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

$selectSubscription = $db->prepare('SELECT subscriptionStatus FROM stripe_consumer WHERE idUser = :idUser');
$selectSubscription->execute(array(
    'idUser' => $_SESSION['id']
));

$subscription = $selectSubscription->fetch();

if($selectSubscription->rowCount() > 0){
    $subscribed = $subscription['subscriptionStatus'];
}else{
    $subscribed = '';
}

?>

    <body>

        <main>

            <div class="allInfoProfil">

                <div class="imageProfil me-4">

                    <?php if($profilePicture != ADDRESS_IMG_PROFIL){ ?>
                    <img src="<?= $profilePicture ?>" alt="Photo de profil" width="300" height="300">
                    <?php } else { ?>
                    <img src="<?= ADDRESS_DEFAULT_PROFIL ?>" alt="Photo de profil" width="300" height="300">
                    <?php } ?>

                    <div class="editProfil">

                        <a href="<?= ADDRESS_SITE ?>profil/modify/<?=$_SESSION['id'] ?>" data-bs-toggle="tooltip" data-bs-title="Modifier le profil" data-bs-placement="top">

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
                    if($subscribed == 'active'){
                    ?>
                        <button class="manageSubLink btn">
                            <a href="<?= ADDRESS_SITE ?>profil/manage/subscription" class="nav-link">Gérer votre abonnement</a>
                        </button>
                    <?php
                    }else{
                    ?>
                        <button class="manageSubLink btn">
                            <a href="<?= ADDRESS_SITE ?>subscribe" class="nav-link">S'abonner</a>
                        </button>
                    <?php
                    }
                    ?>
                    <button class="manageInvoice btn">
                        <a href="<?= ADDRESS_SITE ?>profil/manage/invoice" class="nav-link">Voir mes factures</a>
                    </button>
                </div>

            </div>

        </main>

    </body>

</html>
