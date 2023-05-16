<!DOCTYPE html>
<html lang="fr">
<?php

$title = "Cookorama - Boutique";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

?>
<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="mt-4 d-flex justify-content-center align-items-center">
        <h1>Boutique</h1>
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] == 5):
        ?>
        <a href="<?= ADDRESS_SITE ?>boutique/ajout-produit" class="ms-3">
            <button type="button" class="btn connexionLink shadow">Ajouter un produit</button>
        </a>
        <?php
        endif;
        ?>
    </div>

</main>

</body>

</html>
