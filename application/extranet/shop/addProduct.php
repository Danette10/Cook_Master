<!DOCTYPE html>
<html lang="fr">
<?php
$title = "Cookorama - Ajouter un produit";
include 'ressources/script/head.php';

if (!isset($_SESSION['id']) || (isset($_SESSION['role']) && $_SESSION['role'] != 5)) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}
require_once PATH_SCRIPT . 'header.php';




?>
    <body>

        <main>

            <?php include PATH_SCRIPT . 'messages.php'; ?>

            <div class="text-center mt-4">
                <h1>Ajouter un produit</h1>
            </div>

            <form action="<?= ADDRESS_SITE ?>boutique/ajout-produit/check" method="POST" enctype="multipart/form-data">
                <div class="container mt-4 d-flex flex-column align-items-center">
                    <div class="col-6">
                        <label for="name" style="font-weight: bold;">Nom du produit <span style="color: red;">*</span></label>
                        <input type="text" class="form-control shadow" placeholder="Nom du produit" name="name" required>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="price" style="font-weight: bold;">Prix du produit <span style="color: red;">*</span></label>
                        <input type="text" class="form-control shadow" placeholder="Prix du produit" name="price" required>
                        <small class="form-text text-danger">Le prix doit être en euros</small>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="quantity" style="font-weight: bold;">Quantité du produit <span style="color: red;">*</span></label>
                        <input type="number" class="form-control shadow" placeholder="Quantité du produit" name="quantity" required>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="image" style="font-weight: bold;">Image du produit <span style="color: red;">*</span></label>
                        <input type="file" class="form-control shadow" name="image" required>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="description" style="font-weight: bold;">Description du produit <span style="color: red;">*</span></label>
                        <textarea class="form-control shadow" placeholder="Description du produit" name="description" required></textarea>
                    </div>
                    <div class="col-6 mt-3">
                        <button type="submit" class="btn connexionLink shadow">Ajouter le produit</button>
                    </div>
                </div>
            </form>

        </main>

    </body>

</html>