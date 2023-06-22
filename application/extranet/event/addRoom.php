<?php
if(!isset($_SESSION['id']) || $_SESSION['role'] != 4 && $_SESSION['role'] != 5){
    header('Location: /');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Déclarer une salle";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;
?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="text-center mt-4">
        <h1>Déclarer une salle</h1>
    </div>

    <form action="<?= ADDRESS_SITE ?>évènements/déclarer-une-salle/check" method="POST" enctype="multipart/form-data">
        <div class="container mt-4 d-flex flex-column align-items-center">
            <div class="col-6">
                <label for="name" style="font-weight: bold;">Nom de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" placeholder="Nom de la salle" name="name" required>
            </div>
            <div class="col-6 mt-3">
                <label for="capacity" style="font-weight: bold;">Capacité de la salle <span style="color: red;">*</span></label>
                <input type="number" class="form-control shadow" placeholder="Capacité de la salle" name="capacity" required>
            </div>
            <div class="col-6 mt-3">
                <label for="image" style="font-weight: bold;">Image de la salle <span style="color: red;">*</span></label>
                <input type="file" class="form-control shadow" name="image" required>
            </div>
            <div class="col-6 mt-3">
                <label for="description" style="font-weight: bold;">Description de la salle <span style="color: red;">*</span></label>
                <textarea class="form-control shadow" placeholder="Description de la salle" name="description" required></textarea>
            </div>
            <div class="col-6 mt-3">
                <label for="address" style="font-weight: bold;">Adresse de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="adresse" placeholder="Adresse de la salle" name="address" required>
            </div>
            <div class="col-6 mt-3">
                <label for="city" style="font-weight: bold;">Ville de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="city" placeholder="Ville de la salle" name="city" readonly required>
            </div>
            <div class="col-6 mt-3">
                <label for="zip_code" style="font-weight: bold;">Code postal de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="postal_code" placeholder="Code postal de la salle" name="zip_code" readonly required>
            </div>
            <div class="col-6 mt-3">
                <button type="submit" class="btn btn-success shadow" name="submit">Déclarer la salle</button>
            </div>
        </div>
    </form>

</main>

<script>

    $(document).ready(function() {
        autoCompleteAddress();
    });


</script>

</body>
