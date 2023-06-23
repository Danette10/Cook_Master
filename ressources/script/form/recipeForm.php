<?php
include PATH_SCRIPT . "functions.php";

global $db;

if(isset($_POST['title']) && isset($_POST['recipeDescription']) && isset($_FILES['recipeImage']) && isset($_POST['recipeIngredient1']) && isset($_POST['recipeIngredientQuantity1']) && isset($_POST['recipeIngredientUnit1']) && isset($_POST['nbOfIngredients']) && isset($_POST['recipeStep1'])) {
    $title = htmlspecialchars($_POST['title']);
    $recipeDescription = htmlspecialchars($_POST['recipeDescription']);
    $recipeImage = $_FILES['recipeImage'];
    $recipeIngredient1 = htmlspecialchars($_POST['recipeIngredient1']);
    $recipeIngredientQuantity1 = htmlspecialchars($_POST['recipeIngredientQuantity1']);
    $recipeIngredientUnit1 = htmlspecialchars($_POST['recipeIngredientUnit1']);
    $recipeStep1 = htmlspecialchars($_POST['recipeStep1']);

    $errors = [];

    if (strlen($title) < 2) {
        $errors[] = 'Le titre de la recette doit contenir au moins 2 caractères';
    }

    if (strlen($recipeDescription) < 10) {
        $errors[] = 'La description de la recette doit contenir au moins 10 caractères';
    }

    if ($recipeImage['size'] > 1000000) {
        $errors[] = 'L\'image de la recette ne doit pas dépasser 1Mo';
    }

    if ($recipeImage['error'] != 0) {
        $errors[] = 'Une erreur est survenue lors de l\'upload de l\'image';
    }

    if ($recipeImage['type'] != 'image/jpeg' && $recipeImage['type'] != 'image/png') {
        $errors[] = 'Le format de l\'image n\'est pas valide';
    }

    if (strlen($recipeIngredient1) < 2) {
        $errors[] = 'L\'ingrédient doit contenir au moins 2 caractères';
    }

    if ($recipeIngredientQuantity1 < 0) {
        $errors[] = 'La quantité de l\'ingrédient 1 doit être supérieure à 0';
    }

    if (strlen($recipeStep1) < 10) {
        $errors[] = 'La description de l\'étape 1 doit contenir au moins 10 caractères';
    }


    $nbOfIngredients = $_POST['nbOfIngredients'];
    $nbOfSteps = $_POST['nbOfSteps'];


    $ingredientsArray = [];
    $stepsArray = [];

    $ingredient = array(
        'name' => $recipeIngredient1,
        'quantity' => $recipeIngredientQuantity1,
        'unit' => $recipeIngredientUnit1
    );
    
    $ingredientsArray[0] = $ingredient;
    $stepsArray[0] = $recipeStep1;


    if($nbOfIngredients > 1) {
        for($i = 2; $i <= $nbOfIngredients; $i++) {
            $recipeIngredient = htmlspecialchars($_POST['recipeIngredient' . $i]);
            $recipeIngredientQuantity = htmlspecialchars($_POST['recipeIngredientQuantity' . $i]);
            $recipeIngredientUnit = htmlspecialchars($_POST['recipeIngredientUnit' . $i]);

            if (strlen($recipeIngredient) < 2) {
                $errors[] = 'L\'ingrédient doit contenir au moins 2 caractères';
            }

            if ($recipeIngredientQuantity < 0) {
                $errors[] = 'La quantité de l\'ingrédient ' . $i . ' doit être supérieure à 0';
            }
            
            $ingredient = array(
                'name' => $recipeIngredient,
                'quantity' => $recipeIngredientQuantity,
                'unit' => $recipeIngredientUnit
            );
            
            $ingredientsArray[$i - 1] = $ingredient;
        
        }
    }
        
    if($nbOfSteps > 1) {
        for($i = 2; $i <= $nbOfSteps; $i++) {
            $recipeStep = htmlspecialchars($_POST['recipeStep' . $i]);

            if (strlen($recipeStep) < 10) {
                $errors[] = 'La description de l\'étape ' . $i . ' doit contenir au moins 10 caractères';
            }

            $stepsArray[$i - 1] = $recipeStep;
        }
    }


    if (count($errors) > 0 ) {
        $addRecipeQuery = $db->prepare(('INSERT INTO recipe (title, description, image, user_id) VALUES (:title, :description, :image, :user_id)'));
        $addRecipeQuery->execute(array(
            'title' => $title,
            'description' => $recipeDescription,
            'image' => $recipeImage['name'],
            'user_id' => $_SESSION['id']
        ));

        $recipeId = $db->lastInsertId();

        foreach($ingredientsArray as $ingredient) {
            $addIngredientQuery = $db->prepare(('INSERT INTO recipe_ingredients (ingredientName, ingredientQuantity, unit, recipe_id) VALUES (:ingredientName, :ingredientQuantity, :unit, :recipe_id)'));
            $addIngredientQuery->execute(array(
                'ingredientName' => $ingredient['name'],
                'ingredientQuantity' => $ingredient['quantity'],
                'unit' => $ingredient['unit'],
                'recipe_id' => $recipeId
            ));
        }

        foreach($stepsArray as $step) {
            $addStepQuery = $db->prepare(('INSERT INTO recipe_steps (stepDescription, recipe_id) VALUES (:stepDescription, :recipe_id)'));
            $addStepQuery->execute(array(
                'stepDescription' => $step,
                'recipe_id' => $recipeId
            ));
        }

        $uploadDir = PATH_UPLOAD . $recipeId . '/';
        mkdir($uploadDir);
        move_uploaded_file($recipeImage['tmp_name'], $uploadDir . $recipeImage['name']);

        header("Location: " . ADDRESS_SITE . 'recettes/?type=success&message=Votre inscription a bien été prise en compte, vous allez recevoir un email pour activer votre compte');
        exit();
        
    }else {
        $_SESSION['errors'] = $errors;
        header('Location: ' . ADDRESS_SITE . 'recettes/creation');
    }


}

