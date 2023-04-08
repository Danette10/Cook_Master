<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Profil";
include '../../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';
require_once('../../../vendor/autoload.php');
require_once(PATH_SCRIPT . 'connectDB.php');

if (!isset($_SESSION['id'])) {
    // Redirection si l'utilisateur n'est pas connecté
}

?>

    <body>

        <main>

        </main>

        <?php
        include PATH_SCRIPT . 'functionsJs.php';
        include PATH_SCRIPT . 'footer.php';
        ?>

    </body>

</html>
