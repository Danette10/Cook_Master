<?php
include PATH_SCRIPT . "functions.php";

global $db;

if(isset($_POST['title']) && isset($_POST['recipeDescription']) && isset($_FILES['recipeImage']) && isset($_POST['recipeIngredient1']) && isset($_POST['recipeIngredientQuantity1']) && isset($_POST['recipeIngredientUnit1']) && isset($_POST['recipeStep1'])) {
    $title = htmlspecialchars($_POST['title']);
    $recipeDescription = htmlspecialchars($_POST['recipeDescription']);
    $recipeImage = $_FILES['recipeImage'];
    $recipeIngredient1 = htmlspecialchars($_POST['recipeIngredient1']);
    $recipeIngredientQuantity1 = intval($_POST['recipeIngredientQuantity1']);
    $recipeIngredientUnit1 = htmlspecialchars($_POST['recipeIngredientUnit1']);
    $recipeStep1 = htmlspecialchars($_POST['recipeStep1']);

    $errors = [];

    if (strlen($title) < 2 || strlen($title) > 50) {
        $errors[] = 'Le titre de la recette doit contenir au moins 2 caractères et au maximum 50 caractères';
    }

    if (strlen($recipeDescription) < 10 || strlen($recipeDescription) > 500) {
        $errors[] = 'La description de la recette doit contenir au moins 10 caractères et au maximum 500 caractères';
    }

    if ($recipeImage['size'] > 1000000) {
        $errors[] = 'L\'image de la recette ne doit pas dépasser 1Mo';
    }

    if ($recipeImage['error'] != 0) {
        $errors[] = 'Une erreur est survenue lors de l\'upload de l\'image';
    }

    if ($recipeImage['type'] != 'image/jpeg' && $recipeImage['type'] != 'image/png' && $recipeImage['type'] != 'image/jpg') {
        $errors[] = 'Le format de l\'image n\'est pas valide';
    }

    if (strlen($recipeIngredient1) < 2) {
        $errors[] = 'L\'ingrédient doit contenir au moins 2 caractères';
    }

    if ($recipeIngredientQuantity1 <= 0) {
        $errors[] = 'La quantité de l\'ingrédient 1 doit être supérieure à 0';
    }
    echo $recipeIngredientQuantity1;

    if (strlen($recipeStep1) < 10) {
        $errors[] = 'La description de l\'étape 1 doit contenir au moins 10 caractères';
    }


    $nbOfIngredients = $_POST['nbOfIngredrients'];
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
            $recipeIngredientQuantity = intval($_POST['recipeIngredientQuantity' . $i]);
            $recipeIngredientUnit = htmlspecialchars($_POST['recipeIngredientUnit' . $i]);

            if (strlen($recipeIngredient) < 2 || strlen($recipeIngredient) > 50) {
                $errors[] = 'Le nom de l\'ingrédient doit contenir au moins 2 caractères et au maximum 50 caractères';
            }

            if (!is_numeric($recipeIngredient) &&  $recipeIngredientQuantity <= 0) {
                $errors[] = 'La quantité de l\'ingrédient ' . $i . ' doit être supérieure à 0';
            }
            echo $recipeIngredientQuantity;
    
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



    $creation = date('Y-m-d H:i:s');

    if (count($errors) == 0 ) {
        $file = uploadPicture('recipesImages',$_FILES['recipeImage']);
        if(is_int($file)){
            $errors[] = "Erreur lors de l'upload de l'image";
        }else{
            $profilePicture = $file;
        }

        $addRecipeQuery = $db->prepare(('INSERT INTO recipe (recipeName, description, recipeImage, idUser, creationDate) VALUES (:title, :description, :image, :idUser, :creationDate)'));
        $addRecipeQuery->execute(array(
            'title' => $title,
            'description' => $recipeDescription,
            'image' => $file,
            'idUser' => $_SESSION['id'],
            'creationDate' => $creation
        ));

        $recipeId = $db->lastInsertId();

        foreach($ingredientsArray as $ingredient) {
            $addIngredientQuery = $db->prepare(('INSERT INTO recipe_ingredients (ingredientName, ingredientQuantity, unit, idRecipe) VALUES (:ingredientName, :ingredientQuantity, :unit, :idRecipe)'));
            $addIngredientQuery->execute(array(
                'ingredientName' => $ingredient['name'],
                'ingredientQuantity' => $ingredient['quantity'],
                'unit' => $ingredient['unit'],
                'idRecipe' => $recipeId
            ));
        }

        foreach($stepsArray as $step) {
            $addStepQuery = $db->prepare(('INSERT INTO recipe_steps (stepDescription, idRecipe) VALUES (:stepDescription, :idRecipe)'));
            $addStepQuery->execute(array(
                'stepDescription' => $step,
                'idRecipe' => $recipeId
            ));
        }

        

        header("Location: " . ADDRESS_SITE . 'recettes/?type=success&message=Votre recette a bien été ajoutée');
        exit();
        
    }else {
        $_SESSION['errors'] = $errors;
        header('Location: ' . ADDRESS_SITE . 'recettes/creation');
    }


}else {
    $errors[] = 'Veuillez remplir tous les champs';
    $errors[] = $_POST['title'];
    $errors[] = $_POST['recipeDescription'];
    $errors[] = $_POST['recipeIngredient1'];
    $errors[] = $_POST['recipeIngredientQuantity1'];
    $errors[] = $_POST['recipeIngredientUnit1'];
    $errors[] = $_POST['recipeStep1'];
    
    $_SESSION['errors'] = $errors;
    header('Location: ' . ADDRESS_SITE . 'recettes/creation');
}

