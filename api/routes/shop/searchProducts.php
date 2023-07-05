<?php

require_once __DIR__ . "/../../entities/shop/getProducts.php";

try {

    $products = searchProducts($search);

    if (empty($products)) {
        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "No products found"
        ]);
        exit();
    }

    echo jsonResponse(200, [], [
        "success" => true,
        "products" => $products
    ]);

    die();
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting products: " . $exception->getMessage()
    ]);
}