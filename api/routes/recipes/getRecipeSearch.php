<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/recipes/getRecipeSearch.php";

try {

    $recipe = getRecipeSearch($search);

    if(empty($recipe)){
        echo jsonResponse(200, [], [
            "success" => true,
            "message" => "No recipe found"
        ]);
    } else {
        echo jsonResponse(200, [], [
            "success" => true,
            "recipe" => $recipe
        ]);
    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting recipe: " . $exception->getMessage()
    ]);
}