<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/recipes/getOneRecipe.php";

try {

    $recipe = getRecipe($idRecipe);

    if(empty($recipe)){
        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "Recipe not found"
        ]);
    }else{
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