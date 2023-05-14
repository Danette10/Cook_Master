<?php
include PATH_SCRIPT . "functions.php";

global $db;

if(isset($_POST['title']) && isset($_FILES['recipeImage']) && isset($_POST['recipeDescription']) && isset($_POST['recipeIngredients']) && isset($_POST['recipeSteps'])) {
    $recipeTitle = htmlspecialchars(ucwords(strtolower($_POST['title'])));
    $recipeImage = $_FILES['recipeImage'];
    $recipeDescription = htmlspecialchars(ucwords(strtolower($_POST['recipeDescription'])));
    $recipeIngredients = $_POST['recipeIngredients'];
    $recipeSteps = $_POST['recipeSteps'];

    $errors = [];

    if(strlen($recipeTitle) < 5 || strlen($recipeTitle) > 100) {
        $errors[] = "Le titre doit contenir entre 5 et 100 caractères";
    }


    if(strlen($recipeDescription) < 5 || strlen($recipeDescription) > 1000) {
        $errors[] = "La description doit contenir entre 5 et 1000 caractères";
    }

    if($recipeIngredients <= 0 || $recipeIngredients > 50 ) {
        $errors[] = "Le nombre d'ingrédients doit être compris entre 1 et 50";
    }

    if($recipeSteps <= 0 ) {
        $errors[] = "Le nombre d'étapes doit être supérieur à 0";
    }

    $creator = $_SESSION['id'];

    if(count($errors) == 0) {

        $file = "";

        $file = uploadRecipePicture($recipeImage);

        if(is_int($file)){
            $errors[] = "Erreur lors de l'upload de l'image";
        }else{
            $recipeImage = $file;
        }

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            header("Location: " . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de l\'ajout l\'image de la recette');
            exit();
        }

        $date = date('Y-m-d H:i:s');

        $addRecipe = $db->prepare("INSERT INTO RECIPE (recipeName, description, recipeImage, idUser, creationDate) VALUES (:recipeName, :description, :recipeImage, :creator, :creationDate)");
        $addRecipe->execute([
            'recipeName' => $recipeTitle,
            'description' => $recipeDescription,
            'recipeImage' => $recipeImage,
            'creator' => $creator,
            'creationDate' => $date
        ]);

        $recipeId = $db->lastInsertId();

        for ($i = 1; $i <= $recipeIngredients; $i++) { // Change "<" to "<="
            if (!isset($_POST['ingredientName' . $i])) {
                $errors[] = "Veuillez remplir tous les champs ingrédients.\nVeuillez remplir le champ nom de l'ingrédient " . $i;
            } else {
                $ingredientName = $_POST['ingredientName' . $i];
                if (strlen($ingredientName) < 2 || strlen($ingredientName) > 100) {
                    $errors[] = "Le nom de l'ingrédient " . $i . " doit contenir entre 2 et 100 caractères";
                }

                if (!isset($_POST['ingredientQuantity' . $i])) {
                    $errors[] = "Veuillez remplir le champ quantité de l'ingrédient " . $i;
                } else {
                    $ingredientQuantity = $_POST['ingredientQuantity' . $i];
                }

                if (!isset($_POST['ingredientUnit' . $i])) {
                    $errors[] = "Veuillez remplir le champ unité de l'ingrédient " . $i;
                } else {
                    $ingredientUnit = $_POST['ingredientUnit' . $i];
                }

                if (count($errors) > 0) {
                    $_SESSION['errors'] = $errors;
                    header("Location: " . ADDRESS_SITE . 'recettes');
                    exit();
                } else {
                    $addIngredient = $db->prepare("INSERT INTO RECIPE_INGREDIENTS (ingredientName, ingredientQuantity, unit, idRecipe) VALUES (:ingredientName, :ingredientQuantity, :unit, :idRecipe)");
                    $addIngredient->execute([
                        'ingredientName' => $ingredientName,
                        'ingredientQuantity' => $ingredientQuantity,
                        'unit' => $ingredientUnit,
                        'idRecipe' => $recipeId
                    ]);
                }
            }
        }
        for ($i = 1; $i <= $recipeSteps; $i++) { // Change "<" to "<="
            if (!isset($_POST['step' . $i])) {
                $errors[] = "Veuillez remplir tous les champs étapes.\nVeuillez remplir le champ étape " . $i;
            } else {
                $step = $_POST['step' . $i];
                if (strlen($step) < 2 || strlen($step) > 1000) {
                    $errors[] = "L'étape " . $i . " doit contenir entre 2 et 1000 caractères";
                }

                if (count($errors) > 0) {
                    $_SESSION['errors'] = $errors;
                    header("Location: " . ADDRESS_SITE . 'recettes');
                    exit();
                } else {
                    $addStep = $db->prepare("INSERT INTO RECIPE_STEPS (stepDescription, idRecipe) VALUES (:step, :idRecipe)");
                    $addStep->execute([
                        'step' => $step,
                        'idRecipe' => $recipeId
                    ]);
                }
            }
        }

        header("Location: " . ADDRESS_SITE . 'recettes?type=success&message=La recette a bien été ajoutée');
        exit();
    }else{
        $_SESSION['errors'] = $errors;
        header("Location: " . ADDRESS_SITE . 'recettes');
        exit();
    }
}else{
    header("Location: " . ADDRESS_SITE . 'recettes');
    exit();
}

