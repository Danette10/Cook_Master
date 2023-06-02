<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Panier";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

$total = 0;

$selectCart = $db->prepare('SELECT * FROM cart WHERE idUser = :idUser');
$selectCart->execute([
    'idUser' => $_SESSION['id']
]);

$cart = $selectCart->fetch(PDO::FETCH_ASSOC);

if($selectCart->rowCount() == 0){
    $insertCart = $db->prepare('INSERT INTO cart(idUser) VALUES(:idUser)');
    $insertCart->execute([
        'idUser' => $_SESSION['id']
    ]);
    $idCart = $db->lastInsertId();
}else {
    $idCart = $cart['idCart'];
}
?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="mt-4 d-flex justify-content-center align-items-center">
        <h1>Mon panier</h1>
    </div>

    <?php
    $selectOrders = $db->prepare('SELECT * FROM orders WHERE idUser = :idUser AND idCart = :idCart AND status = 0');
    $selectOrders->execute([
        'idUser' => $_SESSION['id'],
        'idCart' => $idCart
    ]);

    if($selectOrders->rowCount() == 0):
    ?>

    <div class="mt-4 d-flex justify-content-center align-items-center">
        <h2>Votre panier est vide</h2>
    </div>

    <?php
    else: ?>

        <div class="mt-4 d-flex justify-content-center align-items-center">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Produit</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix unitaire</th>
                    <th scope="col">Prix total</th>
                    <th scope="col">Supprimer</th>
                </tr>
                </thead>

    <?php
        $selectProductCart = $db->prepare('SELECT * FROM cart_item WHERE idCart = :idCart');
        $selectProductCart->execute([
            'idCart' => $idCart
        ]);
        $productCart = $selectProductCart->fetchAll(PDO::FETCH_ASSOC);

        foreach($productCart as $product):

            $selectProduct = $db->prepare('SELECT * FROM products WHERE id = :idProduct');
            $selectProduct->execute([
                'idProduct' => $product['id']
            ]);
            $product = $selectProduct->fetch(PDO::FETCH_ASSOC);

            $selectQuantityPerProduct = $db->prepare('SELECT * FROM cart_item WHERE idCart = :idCart AND id = :idProduct');
            $selectQuantityPerProduct->execute([
                'idCart' => $idCart,
                'idProduct' => $product['id']
            ]);

            $quantityPerProduct = $selectQuantityPerProduct->fetch(PDO::FETCH_ASSOC);

            $selectQuantity = $db->prepare('SELECT SUM(quantity) FROM cart_item WHERE idCart = :idCart');
            $selectQuantity->execute([
                'idCart' => $idCart
            ]);

            $quantity = $selectQuantity->fetch(PDO::FETCH_ASSOC);


    ?>
                <tbody id="product_<?= $product['id'] ?>">
                    <tr>
                        <td>
                            <div class="d-flex">
                                <img src="<?= ADDRESS_SITE ?>ressources/images/shopImage/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid rounded" style="max-width: 100px;">
                                <p class="ms-2"><?= $product['name'] ?></p>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
<!--                                --><?php //= ADDRESS_SITE ?><!--panier/moins/--><?php //= $product['id'] ?>
                                <button class="btn btn-danger me-2" onclick="removeProductQuantity(<?= $idCart ?>, <?= $product['id'] ?>)">-</button>
                                <p class="ml-2 mr-2" id="productQuantity_<?= $product['id'] ?>"><?= $quantityPerProduct['quantity'] ?></p>
<!--                                --><?php //= ADDRESS_SITE ?><!--panier/plus/--><?php //= $product['id'] ?>
                                <button class="btn btn-success ms-2" onclick="addProductQuantity(<?= $idCart ?>, <?= $product['id'] ?>)">+</button>
                            </div>
                        </td>
                        <td class="align-middle"><span id="productPrice_<?= $product['id'] ?>"><?= $product['price'] ?></span> €</td>
                        <td class="align-middle"><span id="priceTotalPerProduct_<?= $product['id'] ?>"><?= $product['price'] * $quantityPerProduct['quantity'] ?></span> €</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
<!--                                --><?php //= ADDRESS_SITE ?><!--panier/retirer-produit/--><?php //= $product['id'] ?>
                                <button class="btn btn-danger" onclick="supProduct(<?= $product['id'] ?>)">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>

    <?php
        $total += $product['price'] * $quantityPerProduct['quantity'];

        endforeach;
    ?>

            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center align-items-center">
            <h2>Total : <span id="nbProducts"><?= $quantity['SUM(quantity)'] ?></span> produits pour <span id="priceTotal"><?= $total ?></span> €</h2>
        </div>

        <div class="mt-4 d-flex justify-content-center align-items-center">
            <a href="<?= ADDRESS_SITE ?>panier/valider" class="btn btn-success">Valider mon panier</a>
        </div>

    <?php
    endif;
    ?>

</main>

</body>
