<!DOCTYPE html>
<html lang="fr">
<?php
$title = "Cookorama - Création de recette";
include 'ressources/script/head.php';

if (!isset($_SESSION['id'])) {
    header('Location: ' . ADDRESS_SITE."inscription");
    exit();
}
require_once PATH_SCRIPT . 'header.php';

global $db;





?>
<body>
    <div class="text-center mt-4">
        <h1>Création de recette</h1>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <form action="<?php ADDRESS_SITE ?>recipeForm.php" method="POST" id="recipeForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la recette</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipeImage" class="form-label">Image de la recette</label>
                        <input type="file" class="form-control" id="recipeImage" name="recipeImage" accept="image/jpeg, image/png, image/jpg" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipeDescription" class="form-label">Description de la recette</label>
                        <textarea class="form-control" id="recipeDescription" name="recipeDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recipeIngredients" class="form-label">Ingrédients de la recette</label>
                        <input type="number" class="form-control" id="recipeIngredients" name="recipeIngredients" required></input>
                    </div>
                    <div class="mb-3">
                        <label for="recipeSteps" class="form-label">Nombre d'etapes de la recette</label>
                        <input type="number" class="form-control" id="recipeSteps" name="recipeSteps" oninput="generateStepsFields()" required></input>
                    </div>
                    <div class="mb-3 container" id="stepsOfRecipe"></div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Créer la recette</button>
                    </div>
                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>



</body>
</html>
