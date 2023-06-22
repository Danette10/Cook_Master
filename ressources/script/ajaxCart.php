<?php
require_once ('init.php');
$type = htmlspecialchars($_POST['type']);
$idCart = htmlspecialchars($_POST['cartId']);
$idProduct = htmlspecialchars($_POST['productId']);

global $db;

switch ($type) {

    case 'addProductQuantity':
        $update = $db->prepare('UPDATE cart_item SET quantity = quantity + 1 WHERE idCart = :idCart AND id = :idProduct');
        $update->execute([
            'idCart' => $idCart,
            'idProduct' => $idProduct
        ]);

        $quantity = $db->prepare('SELECT quantity FROM cart_item WHERE idCart = :idCart AND id = :idProduct');
        $quantity->execute([
            'idCart' => $idCart,
            'idProduct' => $idProduct
        ]);

        $quantity = $quantity->fetch(PDO::FETCH_ASSOC);

        if($update){
            echo $quantity['quantity'];
        } else {
            echo "error";
        }

        break;

    case 'removeProductQuantity':
        $update = $db->prepare('UPDATE cart_item SET quantity = quantity - 1 WHERE idCart = :idCart AND id = :idProduct');
        $update->execute([
            'idCart' => $idCart,
            'idProduct' => $idProduct
        ]);

        $quantity = $db->prepare('SELECT quantity FROM cart_item WHERE idCart = :idCart AND id = :idProduct');
        $quantity->execute([
            'idCart' => $idCart,
            'idProduct' => $idProduct
        ]);

        $quantity = $quantity->fetch(PDO::FETCH_ASSOC);

        if($update){
            echo $quantity['quantity'];
        } else {
            echo "error";
        }

        break;

    case 'calculateTotalPrice':
        $select = $db->prepare('SELECT * FROM cart_item WHERE idCart = :idCart');
        $select->execute([
            'idCart' => $idCart
        ]);

        $totalPrice = 0;

        while($cartItem = $select->fetch(PDO::FETCH_ASSOC)){
            $selectProduct = $db->prepare('SELECT * FROM products WHERE id = :idProduct');
            $selectProduct->execute([
                'idProduct' => $cartItem['id']
            ]);

            $product = $selectProduct->fetch(PDO::FETCH_ASSOC);

            $totalPrice += $product['price'] * $cartItem['quantity'];
        }

        echo $totalPrice;
        break;
}