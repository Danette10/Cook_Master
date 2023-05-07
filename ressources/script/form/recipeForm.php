<?php 
include "../init.php";
include PATH_SCRIPT . "functions.php";

global $db;

if(isset($_POST['title']) && isset($_FILES['recipeImage']) && isset($_POST['recipeDescription']) && isset($_POST['recipeIngredients']) && isset($_POST['recipeSteps'])) {
    $recipeTitle = $_POST['title'];
    $recipeImage = $_POST['recipeImage'];
    $recipeDescription = $_POST['recipeDescription'];
    $recipeIngredients = $_POST['recipeIngredients'];
    $recipeSteps = $_POST['recipeSteps'];

    htmlspecialchars(ucwords(strtolower($recipeTitle)));

}




?>