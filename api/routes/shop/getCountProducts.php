<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/shop/getCountProducts.php";

try {

    $nbProducts = getCountProducts();

    if(empty($nbProducts)){

        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "No products found"
        ]);
        exit();

    }else{

        echo jsonResponse(200, [], [
            "success" => true,
            "result" => $nbProducts
        ]);

    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting products: " . $exception->getMessage()
    ]);
}