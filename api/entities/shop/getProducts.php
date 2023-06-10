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