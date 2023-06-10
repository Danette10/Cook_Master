<?php

function getProduct($id){
    global $db;
    $getProductQuery = $db->prepare(
        "
        SELECT * FROM products WHERE type = 2 AND id = :id
        "
    );


    $getProductQuery->execute([
        "id" => $id
    ]);

    return $getProductQuery->fetchAll(PDO::FETCH_ASSOC);
}