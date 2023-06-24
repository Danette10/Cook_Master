<!DOCTYPE html>
<html lang="fr">
<?php
$title = "Cookorama - Création de recette";
include 'ressources/script/head.php';

if (!isset($_SESSION['id'])) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}
require_once PATH_SCRIPT . 'header.php';

global $db;


echo '<div id="infoPanel">';
if (!empty($_SESSION['errors']) && isset($_SESSION['errors'])) {
    echo '<div class="alert alert-danger mt-4 pb-1" role="alert">';

    for ($i = 0; $i < count($_SESSION['errors']); $i++) {
       $element = $_SESSION['errors'][$i];
       echo '<h5 class="fw-bold">- ' . $element . '</h5>';
    }
    echo '</div>';
    unset($_SESSION['errors']);
}
echo '</div>';


?>
<body>
    <div class="text-center mt-4">
        <h1>Création de recette</h1>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <form action="<?= ADDRESS_SITE ?>recettes/creation/check" method="post" id="recipeForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la recette</label>
                        <input type="text" class="form-control" id="recipeCreationTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipeImage" class="form-label">Image de la recette</label>
                        <input type="file" class="form-control" id="recipeImage" name="recipeImage" accept="image/jpeg, image/png, image/jpg" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipeDescription" class="form-label">Description de la recette</label>
                        <textarea class="form-control" id="recipeCreationDescription" name="recipeDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="row" id="recipeIngredientsRow">
                            <div class="col-4">
                                <input type="text" class="form-control mb-3" id="recipeIngredient1" name="recipeIngredient1" placeholder="Nom de l'ingrédient" required>
                            </div>
                            <div class="col-3">
                                <input type="number" class="form-control" id="recipeIngredientQuantity1" name="recipeIngredientQuantity1" placeholder="Quantité" required>
                            </div>
                            <div class="col-3">
                                <select class="form-select" id="recipeIngredientUnit1" name="recipeIngredientUnit1" required>
                                    <option selected value="g">g</option>
                                    <option value="kg">kg</option>
                                    <option value="ml">ml</option>
                                    <option value="cl">cl</option>
                                    <option value="l">l</option>
                                    <option value="cuillère à café">cuillère à café</option>
                                    <option value="cuillère à soupe">cuillère à soupe</option>
                                    <option value="verre">verre</option>
                                    <option value="pincée">pincée</option>
                                </select>
                            </div>
                            <div class="col-2"></div>
                        </div>
                        <div class="row" id="recipeIngredientsAddedRow"></div>
                        <div class="row pt-4">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-outline-warning" id="addIngredientBtn" onclick="addIngredient()">Ajouter un ingrédient</button>
                                <input type="hidden" id="nbOfIngredrients" name="nbOfIngredrients" value=1 >
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                    <div class="mb-3" id="recipeIngredientsList"></div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-10">
                                <label for="recipeStep1" class="form-label">Etape 1</label>
                                <textarea class="form-control mb-3" id="recipeStep1" name="recipeStep1" rows="3" placeholder="Description de l'étape 1..." required></textarea>
                            </div>
                        </div>
                        <div class="row" id="recipeStepsAddedRow"></div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-outline-warning" id="addStepBtn" onclick="addStep()">Ajouter une étape</button>
                                <input type="hidden" id="nbOfSteps" name="nbOfSteps" value=1 >
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="stepsOfRecipe"></div>
                    <div class="mb-3 text-center">
                        <button type="submit" id="recepiCreationSubmit" class="btn btn-primary">Créer la recette</button>
                    </div>
                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

<script>
    $('#recipeForm').submit(function (e) {

        if(confirm("Voulez-vous vraiment soumettre le formulaire ?")) {
            recipeErrors = recipeIngredientsRule();
            ingredientsErrors = recipeStepsRule();
            console.log(recipeErrors);
            console.log(ingredientsErrors);
            if (!recipeNameRule()) {
                alert('Le nom de la recette doit contenir entre 3 et 50 caractères !');
                return false;
            }
            if (!recipeDescriptionRule()) {
                alert('La description de la recette doit contenir entre 10 et 500 caractères !');
                return false;
            }
            if (recipeErrors.lenght === 0) {
                for (const $error of recipeErrors) {
                    alert($error);
                }
                return false;
            }
            if (ingredientsErrors.lenght === 0) {
                for (const $error of ingredientsErrors) {
                    alert($error);
                }
                return false;
            }
            
        } else {
            return false;
        }

        document.getElementById('nbOfIngredrients').removeAttribute('hidden');
        document.getElementById('nbOfSteps').removeAttribute('hidden');
});
</script>

</body>
</html>