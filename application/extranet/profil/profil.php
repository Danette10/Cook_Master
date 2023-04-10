<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Profil";
include '../../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';
require_once('../../../vendor/autoload.php');

if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}

$selectInfo = $db->prepare('SELECT * FROM user WHERE id = :id');
$selectInfo->execute(array(
    'id' => $_SESSION['id']
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

?>

    <body>

        <main>

            <div class="allInfoProfil">

                <div class="imageProfil me-4">

                    <img src="<?= $profilePicture ?>" alt="Photo de profil" width="300" height="300">

                    <div class="editProfil">

                        <a href="<?= ADDRESS_SITE ?>profil/modification/<?=$_SESSION['id'] ?>" data-bs-toggle="tooltip" data-bs-title="Modifier le profil" data-bs-placement="top">

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
                </div>

            </div>

        </main>

        <?php
        include PATH_SCRIPT . 'functionsJs.php';
        include PATH_SCRIPT . 'footer.php';
        ?>

    </body>

</html>
