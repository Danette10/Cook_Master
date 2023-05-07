<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Dashboard";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 5) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}

$selectUsersBan = $db->prepare("SELECT COUNT(*) AS nbUsersBan FROM users WHERE role = -2");
$selectUsersBan->execute();
$nbUsersBan = $selectUsersBan->fetch();

$selectUsersPresta = $db->prepare("SELECT COUNT(*) AS nbUsersPresta FROM users WHERE role = 4");
$selectUsersPresta->execute();
$nbUsersPresta = $selectUsersPresta->fetch();

$selectUsersSub = $db->prepare("SELECT COUNT(*) AS nbUsersSub FROM users WHERE role = 2 OR role = 3");
$selectUsersSub->execute();
$nbUsersSub = $selectUsersSub->fetch();

$selectUsersAdmin = $db->prepare("SELECT COUNT(*) AS nbUsersAdmin FROM users WHERE role = 5");
$selectUsersAdmin->execute();
$nbUsersAdmin = $selectUsersAdmin->fetch();

$selectUsersPending = $db->prepare("SELECT COUNT(*) AS nbUsersPending FROM users WHERE role = 0 OR role = -1");
$selectUsersPending->execute();
$nbUsersPending = $selectUsersPending->fetch();

$selectTotalUsers = $db->prepare("SELECT COUNT(*) AS totalUsers FROM users WHERE role > 0");
$selectTotalUsers->execute();
$totalUsers = $selectTotalUsers->fetch();

?>

<body>

<main>

    <h1 class="text-center mt-3 mb-3">Dashboard</h1>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="d-flex justify-content-center">

        <div id="usersInfos" class="col-md-4 me-3" style="background: white; padding: 10px; border: 1px solid lightgray; border-radius: 5px;">

            <h5><strong>Informations utilisateurs</strong></h5>

            <div id="allInfosUsers" class="d-flex flex-wrap align-items-baseline">

                <div class="col-md-6">

                    <div id="userBan" class="d-flex flex-column" style="border: 1px solid lightgray; border-radius: 5px; margin-right: 5px; margin-bottom: 5px; padding: 10px;">

                        <img src="<?= ADDRESS_IMG ?>dashboard/ban.png" alt="ban" width="35" height="35">
                        <span class="ml-2 mt-3"><strong>Utilisateurs bannis</strong></span>
                        <span class="ml-2 mt-3"><?= $nbUsersBan['nbUsersBan'] ?></span>

                    </div>

                    <div class="d-flex flex-column" id="userPresta" style="border: 1px solid lightgray; border-radius: 5px; margin-right: 5px; margin-bottom: 5px; padding: 10px;">

                        <img src="<?= ADDRESS_IMG ?>dashboard/prestataire.png" alt="presta" width="35" height="35">
                        <span class="ml-2 mt-3"><strong>Prestataires</strong></span>
                        <span class="ml-2 mt-3"><?= $nbUsersPresta['nbUsersPresta'] ?></span>

                    </div>

                </div>

                <div class="mt-3 col-md-6">

                    <div class="d-flex flex-column" id="userSub" style="border: 1px solid lightgray; border-radius: 5px; margin-bottom: 5px; padding: 10px;">

                        <img src="<?= ADDRESS_IMG ?>dashboard/sabonner.png" alt="sub" width="35" height="35">
                        <span class="ml-2 mt-3"><strong>Utilisateurs abonnés</strong></span>
                        <span class="ml-2 mt-3"><?= $nbUsersSub['nbUsersSub'] ?></span>

                    </div>

                    <div class="d-flex flex-column" id="userAdmin" style="border: 1px solid lightgray; border-radius: 5px; padding: 10px;">

                        <img src="<?= ADDRESS_IMG ?>dashboard/admin.png" alt="admin" width="35" height="35">
                        <span class="ml-2 mt-3"><strong>Administrateurs</strong></span>
                        <span class="ml-2 mt-3"><?= $nbUsersAdmin['nbUsersAdmin'] ?></span>

                    </div>

                </div>

            </div>

        </div>

        <div id="totalUsers" class="col-md-4" style="background: white; padding: 10px; border: 1px solid lightgray; border-radius: 5px;">

            <h5 style="font-weight: bold;">Nombre total d'utilisateurs<span style="float: right; font-size: 1.2em;"><?= $totalUsers['totalUsers'] ?></span></h5>

            <div class="d-flex" style="margin-right: 5px; margin-top: 24px; height: 85%;">

                <a class="col-md-6 text-dark" href="<?= ADDRESS_SITE ?>dashboard/admin/users">

                    <div id="allUsers" class="d-flex align-items-center" style="background: white; padding: 5px; border: 1px solid lightgray; border-radius: 5px; margin-right: 5px; height: 100%;">

                        <div class="d-flex flex-column ms-3" style="margin-bottom: 5px;">

                            <img src="<?= ADDRESS_IMG ?>dashboard/user.png" alt="allUsers" width="90" height="90">
                            <span class="ml-2 mt-3"><strong>Voir les utilisateurs</strong></span>
                            <span class="ml-2 mt-3"><?= $totalUsers['totalUsers'] ?></span>

                        </div>

                    </div>

                </a>

                <a class="col-md-6 text-dark" href="<?= ADDRESS_SITE ?>dashboard/admin/users-pending">

                    <div id="usersPending" class="d-flex align-items-center" style="background: white; padding: 10px; border: 1px solid lightgray; border-radius: 5px; height: 100%;">

                        <div class="d-flex flex-column ms-3" style="margin-bottom: 5px;">

                            <img src="<?= ADDRESS_IMG ?>dashboard/pending.png" alt="attente" width="90" height="90">
                            <span class="ml-2 mt-3"><strong>En attente</strong></span>
                            <span class="ml-2 mt-3"><?= $nbUsersPending['nbUsersPending'] ?></span>

                        </div>

                    </div>

                </a>

            </div>

        </div>

    </div>

</main>

</body>

</html>
