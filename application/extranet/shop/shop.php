<!DOCTYPE html>
<html lang="fr">
<?php

$title = "Cookorama - Boutique";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

?>
<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="mt-4 d-flex justify-content-center align-items-center">
        <h1 class="lang-shop"></h1>
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] == 5):
        ?>
        <a href="<?= ADDRESS_SITE ?>boutique/ajout-produit" class="ms-3">
            <button type="button" class="btn connexionLink shadow lang-shop-add"></button>
        </a>
        <?php
        endif;
        ?>
    </div>

    <div class="allProducts col-md-8 col-sm-2 d-flex flex-wrap justify-content-center" style="margin: 15px auto;">

        <?php
        $products = $db->query('SELECT * FROM products WHERE type = 2');
        while($product = $products->fetch()):
            ?>
            <a href="<?= ADDRESS_SITE ?>boutique/produit/<?= $product['id'] ?>" class="productLink">
                <div class="product col-md-4 col-sm-12 p-3 d-flex flex-column justify-content-between">
                    <div class="productImage">
                        <img src="<?= ADDRESS_SITE ?>ressources/images/shopImage/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="300" height="300" class="img-responsive rounded">
                    </div>
                    <div class="productInfos mt-2">
                        <div>
                            <h2><?= $product['name'] ?> - <em><?= $product['price'] ?></em> €</h2>
                            <p><?= cutString($product['description'], 114); ?></p>
                        </div>
                    </div>
                    <div>
                        <p><span class="lang-shop-quantity"></span> <?= $product['quantity'] ?></p>
                        <?php
                        if(isset($_SESSION['role']) && $_SESSION['role'] == 5):
                            ?>
                            <a href="<?= ADDRESS_SITE ?>boutique/modifier-produit/<?= $product['id'] ?>">
                                <button type="button" class="btn connexionLink shadow lang-shop-modify"></button>
                            </a>
                            <a href="<?= ADDRESS_SITE ?>boutique/supprimer-produit/<?= $product['id'] ?>">
                                <button type="button" class="btn connexionLink shadow lang-shop-delete"></button>
                            </a>
                        <?php
                        endif;
                        ?>
                        <a href="<?= ADDRESS_SITE ?>boutique/ajout-panier/<?= $product['id'] ?>">
                            <img src="<?= ADDRESS_IMG ?>shopping-cart.png" alt="Ajouter au panier" width="30" class="img-responsive rounded">
                        </a>
                    </div>
                </div>
            </a>
        <?php
        endwhile;
        ?>

    </div>

</main>

</body>

</html>
