<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/shop/getOneProduct.php";

try {

    $product = getProduct($idProduct);

    if(empty($product)){
        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "Product not found"
        ]);
    }else{
        echo jsonResponse(200, [], [
        "success" => true,
        "products" => $product
        ]);
    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting products: " . $exception->getMessage()
    ]);
}