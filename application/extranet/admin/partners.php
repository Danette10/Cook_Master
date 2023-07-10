<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Partenaires";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 5) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}

global $db;

?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="d-flex justify-content-center align-items-center">
        <h1 class="text-center mt-3 mb-3 lang-partner me-3"></h1>
        <div>
            <a href="<?= ADDRESS_SITE ?>admin/partenaires/ajout">
                <button type="button" class="btn connexionLink shadow lang-addPartner"></button>
            </a>
        </div>
    </div>


</main>

</body>
