<!DOCTYPE html>
<html lang="fr">
<?php
global $db;
$select = $db->query('SELECT * FROM recipe WHERE idRecipe = ' . $idRecipe);
$recipe = $select->fetch(PDO::FETCH_ASSOC);
$title = "Cookorama - " . $recipe['recipeName'];
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

$getRecipeIngredients = $db->prepare("SELECT * FROM recipe_ingredients WHERE idRecipe = :idRecipe");
$getRecipeIngredients->execute([
    'idRecipe' => $idRecipe
]);
$recipeIngredients = $getRecipeIngredients->fetchAll(PDO::FETCH_ASSOC);

$getRecipeSteps = $db->prepare("SELECT * FROM recipe_steps WHERE idRecipe = :idRecipe");
$getRecipeSteps->execute([
    'idRecipe' => $idRecipe
]);
$recipeSteps = $getRecipeSteps->fetchAll(PDO::FETCH_ASSOC);

?>
<body>
    <main>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <h1 class="text-center mt-4"><?= $recipe['recipeName'] ?></h1>
                <div class="mt-4 text-center">
                    <img src="<?= ADDRESS_SITE ?>ressources/images/recipesImages/<?= $recipe['recipeImage'] ?>" alt="<?= $recipe['recipeName'] ?>" width="300" class="img-responsive img-fluid rounded">
                </div>
                <div class="mt-4 text-center">
                    <p><i><?=wordwrap($recipe['description'],60,"\n",true) ?></i></p>
                </div>
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row mt-4 ">
            <div class="col-4 text-center">
                <u><h3 class="lang-recipe-ingredient-list"></h3></u>
                <?php
                echo '
                <div class="row mt-5" style="padding-left: 8rem; margin-right:5px; ">';
                foreach ($recipeIngredients as $recipeIngredient) {
                    echo '
                    <div class="col-6 text-center" style="background: #fe9c90; border: 1px solid #333; border-radius: 10px;">
                        <p>' . $recipeIngredient['ingredientName'] . '</p>
                    </div>
                    <div class="col-2 text-center" style="background: #fe9c90; border: 1px solid #333; border-radius: 10px;">
                        <p>' . $recipeIngredient['ingredientQuantity'] . '</p>
                    </div>
                    <div class="col-4 text-center" style="background: #fe9c90; border: 1px solid #333; border-radius: 10px;">
                        <p>' . $recipeIngredient['unit'] . '</p>
                    </div>';
                    
                }
                echo '</div>';
                ?>
            </div>
            <div class="col-4">
                <h3 class="text-center lang-steps"></h3>
                <?php
                    foreach($recipeSteps as $step) {
                        echo '
                        <p class="mt-4" style="background: #fe9c90; border: 1px solid #333; border-radius: 10px;padding-left:5px; padding-right:5px;">'.wordwrap($step['stepDescription'],75,"\n",true).' </p>
                        ';
                    }
                ?>
            </div>
            <div class="col-4"></div>
        </div>
    </main>
</body>

</html>