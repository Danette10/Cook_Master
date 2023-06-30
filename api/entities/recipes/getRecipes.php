<?php

function getRecipes(){
    global $db;
    $getRecipesQuery = $db->prepare(
        "
        SELECT * FROM recipe ORDER BY idRecipe DESC
        "
    );


    $getRecipesQuery->execute();

    return $getRecipesQuery->fetchAll(PDO::FETCH_ASSOC);
}