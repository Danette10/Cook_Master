<?php

require_once __DIR__ . "/../../entities/shop/getProducts.php";

try {

    $products = searchProducts($search);

    if (empty($products)) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "No products found"
        ]);
        exit();
    }

    http_response_code(200);
    echo json_encode([
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