<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/recipes/getRecipes.php";

try {

    $recipes = getRecipes();

    if(empty($recipes)){

        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "No recipes found"
        ]);
        exit();

    }else{

        echo jsonResponse(200, [], [
            "success" => true,
            "products" => $recipes
        ]);

    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting recipes: " . $exception->getMessage()
    ]);
}