<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Toutes nos recettes";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}

$echo $currentPage;

$nbOfPages = getNbrOfPages();
$perPage = 8;
$offset = ($currentPage * $perPage) * $perPage;
$recipes = getRecipes($offset,$perPage);


?>

<body>
    <div class="text-center mt-4">
        <h1>Recettes</h1>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 row">
                <div class="col-6">
                    <input type="text" class="form-control shadow" placeholder="Rechercher une recette" id="recipeSearchBar">
                </div>
                <div class="col-3">
                    <select class="form-select shadow">
                        <option selected>Filtres</option>
                        <option value="1">Les plus aimés</option>
                        <option value="2">Les moins aimés</option>
                        <option value="3">Date de création</option>
                    </select>
                </div>
                <div class="col-3">
                    <button type="button" class="btn connexionLink shadow">Crée ta recette</button>
                </div>
                
                
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row text-center mt-5 ">
            <?php 
            foreach($recipes as $recipe){
                echo '
                <div class="col-sm-3  mt-2">
                    <div class="card board" style="width: 18rem;">
                        <img src="'.$recipe['recipeImage'].'" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h4>'.$recipe['recipeName'].'</h4>
                            <p class="card-text">'.$recipe['description'].'</p>
                        </div>
                    </div>
                </div>';
                
            }
            ?>
        </div>
        <div class="row text-center">
            <a class="btn paginationBtn shadow" href="#" role="button">1</a>
        </div>
    </div>



</body>


</html>