<?php
include PATH_SCRIPT . "functions.php";

global $db;

$selectIfUserCartExist = $db->prepare('SELECT * FROM cart WHERE idUser = :idUser');
$selectIfUserCartExist->execute([
    'idUser' => $_SESSION['id']
]);

if($selectIfUserCartExist->rowCount() == 0){
    $insertCart = $db->prepare('INSERT INTO cart(idUser) VALUES(:idUser)');
    $insertCart->execute([
        'idUser' => $_SESSION['id']
    ]);
}

$selectCart = $db->prepare('SELECT * FROM cart WHERE idUser = :idUser');
$selectCart->execute([
    'idUser' => $_SESSION['id']
]);

$cart = $selectCart->fetch(PDO::FETCH_ASSOC);

$insertCartItems = $db->prepare('INSERT INTO cart_item(idCart, id, quantity) VALUES(:idCart, :idProduct, :quantity)');
$insertCartItems->execute([
    'idCart' => $cart['idCart'],
    'idProduct' => $idProduct,
    'quantity' => 1
]);

$selectOrders = $db->prepare('SELECT * FROM orders WHERE idUser = :idUser AND idCart = :idCart AND status = 0');
$selectOrders->execute([
    'idUser' => $_SESSION['id'],
    'idCart' => $cart['idCart']
]);

if($selectOrders->rowCount() == 0){
    $insertOrder = $db->prepare('INSERT INTO orders(idUser, idCart, idInvoice, pathInvoice, status) 
VALUES(:idUser, :idCart, :idInvoice, :pathInvoice, :status)');
    $insertOrder->execute([
        'idUser' => $_SESSION['id'],
        'idCart' => $cart['idCart'],
        'idInvoice' => '',
        'pathInvoice' => '',
        'status' => 0
    ]);
}

header('Location: ' . ADDRESS_SITE . 'boutique?message=Le produit a bien été ajouté au panier !&type=success');
exit();