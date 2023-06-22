<?php

function getRecipe($id){
    global $db;
    $getRecipeQuery = $db->prepare(
        "
        SELECT * FROM recipe WHERE idRecipe = :id
        "
    );


    $getRecipeQuery->execute([
        "id" => $id
    ]);

    return $getRecipeQuery->fetchAll(PDO::FETCH_ASSOC);
}