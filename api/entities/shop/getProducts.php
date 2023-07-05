<?php

function getProducts(){
    global $db;
    $getProductsQuery = $db->prepare(
        "
        SELECT * FROM products WHERE type = 2 ORDER BY id DESC
        "
    );


    $getProductsQuery->execute();

    return $getProductsQuery->fetchAll(PDO::FETCH_ASSOC);
}

function searchProducts($search){
    
    global $db;
    $searchProductsQuery = $db->prepare(
        "
        SELECT * FROM products WHERE type = 2 AND name LIKE '%$search%' ORDER BY id DESC
        "
    );

    $searchProductsQuery->execute();

    return $searchProductsQuery->fetchAll(PDO::FETCH_ASSOC);
}