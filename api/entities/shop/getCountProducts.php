<?php

function getCountProducts(){
    global $db;
    $getCountProductQuery = $db->prepare(
        "
        SELECT COUNT(*) as nbProduct FROM products
        "
    );

    $getCountProductQuery->execute();

    $count = $getCountProductQuery->fetch(PDO::FETCH_ASSOC);

    return $count['nbProduct'];

}