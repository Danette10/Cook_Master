<?php

function getRecipeSearch($search){
    global $db;
    $getRecipeQuery = $db->prepare(
        "
        SELECT * FROM recipe WHERE 
                                recipeName LIKE '%$search%' OR 
                                description LIKE '%$search%'
                            ORDER BY recipeName ASC;
        "
    );

    $getRecipeQuery->execute();

    return $getRecipeQuery->fetchAll(PDO::FETCH_ASSOC);
}