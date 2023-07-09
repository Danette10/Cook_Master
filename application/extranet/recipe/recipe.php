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

$authorInfos = $db->prepare("SELECT * FROM users WHERE idUser = :idUser");
$authorInfos->execute([
    'idUser' => $recipe['idUser']
]);
$authorInfos = $authorInfos->fetch(PDO::FETCH_ASSOC);
$authorProfilePicture = $authorInfos['profilePicture'];
?>
<body>
    <main>
        <div class="d-flex">
            <div class="col-4 text-center"></div>
            <div class="col-4">
                <h1 class="text-center mt-4"><?= $recipe['recipeName'] ?></h1>
            </div>
            <div class="col-4"></div>
        </div>
        <div class="d-flex">
            <div class="col-3 text-center pt-5">
            <?php
            if(isset($_SESSION['role']) && $_SESSION['role'] == 5):
            ?>
            <a href="<?= ADDRESS_SITE ?>recette/supprimer-recette/<?= $recipe['idRecipe'] ?>" class="">
                <button type="button" class="btn btn-danger shadow lang-shop-delete"></button>
            </a>
            <?php
            endif;
            ?>
            </div>
            <div class="col-6 mt-4 text-center">
                <img src="<?= ADDRESS_SITE ?>ressources/images/recipesImages/<?= $recipe['recipeImage'] ?>" alt="<?= $recipe['recipeName'] ?>" class="img-fluid rounded">
            </div>
            <div class="col-3"></div>
        </div>
        <div class="d-flex">
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <div class="mt-4 text-center">
                    <p><i><?=wordwrap($recipe['description'],60,"\n",true) ?></i></p>
                </div>
            </div>
            <div class="col-4"></div>
        </div>
        <div class="d-flex mt-4 justify-content-center">
            <div class="col-5 ms-4">
                <h2 class="lang-recipe-ingredient-list"></h2>
                <?php foreach($recipeIngredients as $ingredient):
                    $ingredientName = $ingredient['ingredientName'];

                    ?>
                    <div class="d-flex">
                        <div class="col-4 d-flex align-items-center">
                            <p><?= $ingredient['ingredientName'] . '<strong> ' . $ingredient['ingredientQuantity'] . ' ' . $ingredient['unit'] . '</strong>' ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-4">
                <h2 class="lang-steps"></h2>
                <?php
                $count = 1;
                foreach($recipeSteps as $step):
                        $stepDescription = $step['stepDescription'];
                        ?>
                        <div class="d-flex">
                            <div class="col-4 d-flex align-items-center">
                                <p>
                                    <strong class="fs-5">
                                        <span class="lang-step"></span>
                                        <?= $count++ ?> :
                                    </strong>
                                    <?= $step['stepDescription'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
            </div>
        </div>
        <div class="text-end me-3 mt-3">
            <?php
            $select = $db->query('SELECT idUser FROM recipe WHERE idRecipe = ' . $idRecipe);
            $idUser = $select->fetch(PDO::FETCH_ASSOC);
            $idUser = $idUser['idUser'];
            $select = $db->query('SELECT firstName, lastName FROM users WHERE idUser = ' . $idUser);
            $authorInfos = $select->fetch(PDO::FETCH_ASSOC);
            ?>
            <p>Publié par <strong><?= $authorInfos['firstName'] . ' ' . $authorInfos['lastName'] ?></strong> le <strong><?= date('d/m/Y à H:i', strtotime($recipe['creationDate'])) ?></strong></p>
        </div>
    </main>
</body>

</html>