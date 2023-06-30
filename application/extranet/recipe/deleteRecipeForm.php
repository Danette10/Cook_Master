<!DOCTYPE html>
<html lang="fr">
<?php
$title = "Cookorama - Supprimer cette recette";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';
?>
<body>
    <main>
        <form action="<?= ADDRESS_SITE ?>recette/suppresion" method="POST">
            <div class="row mt-5">
                <div class="col-4"></div>
                <div class="col-4">
                    <h3 class="lang-why-delete-recipe"></h3>
                    <input type="number" name="idRecipe" value="<?= $id ?>" hidden>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row mt-5">
                <div class="col-4"></div>
                <div class="col-4">
                    <textarea name="reason" class="form-control" rows="5" placeholder="Raison de la suppression" required></textarea>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row mt-5">
                <div class="col-4"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-danger lang-delete-recipe"></button>
                </div>
                <div class="col-4"></div>
            </div>
        </form>
    </main>
</body>
</html>
