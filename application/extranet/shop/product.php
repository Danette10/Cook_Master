<!DOCTYPE html>
<html lang="fr">
<?php
global $db;
$select = $db->query('SELECT * FROM products WHERE id = ' . $idProduct);
$product = $select->fetch(PDO::FETCH_ASSOC);

$title = "Cookorama - " . $product['name'];
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';



?>
<body>

<main>

<?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="mt-4 d-flex justify-content-center align-items-center">
        <h1><?= $product['name'] ?> - <em><?= $product['price'] ?></em> €</h1>
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] == 5):
        ?>
        <a href="<?= ADDRESS_SITE ?>boutique/modifier-produit/<?= $product['id'] ?>" class="ms-3">
            <button type="button" class="btn connexionLink shadow">Modifier</button>
        </a>
        <a href="<?= ADDRESS_SITE ?>boutique/supprimer-produit/<?= $product['id'] ?>" class="ms-3">
            <button type="button" class="btn connexionLink shadow">Supprimer</button>
        </a>
        <?php
        endif;
        ?>
    </div>

    <div class="product col-md-8" style="margin: 15px auto;">
        <div class="productImage">
            <img src="<?= ADDRESS_SITE ?>ressources/images/shopImage/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="300" class="img-responsive rounded">
        </div>
        <div class="productInfos mt-2">
            <div>
                <p><?= $product['description'] ?></p>
                <p>Quantité : <?= $product['quantity'] ?></p>
            </div>
        </div>
    </div>

</main>

</body>
</html>
