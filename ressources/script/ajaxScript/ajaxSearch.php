<?php
require_once('../init.php');
$type = htmlspecialchars($_POST['type']);
$search = htmlspecialchars($_POST['search']);

global $db;

switch ($type):
    case 'recipes':
        $select = $db->prepare('SELECT * FROM recipe WHERE recipeName LIKE :search ORDER BY recipeName');
        $select->execute([
            'search' => '%' . $search . '%'
        ]);

        $recipes = $select->fetchAll(PDO::FETCH_ASSOC);

        foreach ($recipes as $recipe):
            ?>
            <div class="recipe">
                <a href="<?= ADDRESS_SITE.'recette/'.$recipe['idRecipe']; ?>" class="d-flex align-items-center p-3 text-dark linkRecipe">
                    <img src="<?= ADDRESS_SITE.'ressources/images/recipesImages/'.$recipe['recipeImage']; ?>" width="40" alt="<?= $recipe['recipeName']; ?>" class="me-3 rounded">
                    <h5><?= $recipe['recipeName']; ?></h5>
                </a>
            </div>
            <?php
        endforeach;
        break;
    endswitch;