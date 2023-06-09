<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/shop/getProducts.php";

try {

    $products = getProducts();

    echo jsonResponse(200, [], [
        "success" => true,
        "products" => $products
    ]);
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting products: " . $exception->getMessage()
    ]);
}