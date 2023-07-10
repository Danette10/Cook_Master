<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Ajout d'un partenaire";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 5) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}
?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <h1 class="text-center mt-3 mb-3 lang-addPartner me-3"></h1>

    <div class="d-flex justify-content-center align-items-center">
        <div class="col-md-6">
            <form action="<?= ADDRESS_SITE ?>admin/partenaires/ajout" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label lang-name"></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label lang-link"></label>
                    <input type="text" class="form-control" id="link" name="link" required>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label lang-image"></label>
                    <input type="file" class="form-control" id="files" name="files" accept="image/png, image/jpeg" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label lang-description"></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
            </form>
        </div>
    </div>

</main>

</body>
