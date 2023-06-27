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

$nbOfPages = getNbrOfPages();
$perPage = 8;
$offset = ($currentPage * $perPage) - $perPage;
$recipes = getRecipes($offset,$perPage);

?>

<body>
    <div class="text-center mt-4">
        <h1 class="lang-recipe"></h1>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7 row">
                <div class="col-6">
                    <input type="text" class="form-control shadow lang-placeholder-searchRecipe" placeholder="Rechercher une recette" id="recipeSearchBar">
                </div>
                <div class="col-3">
                    <select class="form-select shadow">
                        <option selected class="lang-filter">Filtres</option>
                        <option value="1" class="lang-recipe-mostLiked"></option>
                        <option value="2" class="lang-recipe-lessLiked"></option>
                        <option value="3" class="lang-recipe-creationDate"></option>
                    </select>
                </div>
                <?php
                if(isset($_SESSION['id'])):
                ?>
                <div class="col-3">
                    <a href="<?= ADDRESS_SITE ?>recettes/creation">
                        <button type="button" class="btn connexionLink shadow lang-recipe-create"></button>
                    </a>
                </div>
                <?php
                endif;
                ?>
                
                
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row text-center mt-5 ">
            <div class="card-group">
                <?php 
                foreach($recipes as $recipe){
                    echo '
                    <a href="'.ADDRESS_SITE.'recettes/'.$recipe['idRecipe'].'">
                        <div class="col-sm-3  mt-3">
                            <div class="card board" style="width: 18rem;">
                                <img src="'. ADDRESS_SITE . 'ressources/images/recipesImages/'.$recipe['recipeImage'].'" width="300" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4>'.cutString($recipe['recipeName'],20).'</h4>
                                    <p class="card-text">'.cutString($recipe['description'],55).'</p>
                                </div>
                            </div>
                        </div>
                    </a>';
                    
                }
                ?>
            </div>
        </div>
        <div class="row text-center mt-4 ">
            <div class="col"></div>
            <div class="col">
            <nav>
                <ul class="pagination">
                    <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                    <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                        <a href="recettes?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                    </li>
                    <?php for($page = 1; $page <= $nbOfPages; $page++): 
                        //<!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                        if ($page == $currentPage) {
                            echo '
                            <li class="page-item disabled">
                                <a href="'.ADDRESS_SITE.'recettes?page='. $page .'" class="page-link">'.$page.'</a>
                            </li>';
                        }else {
                            echo'
                            <li class="page-item">
                                <a href="'.ADDRESS_SITE.'recettes?page='. $page .'" class="page-link">'.$page.'</a>
                            </li>';
                        }
                        
                     endfor ?>
                        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                    <li class="page-item <?= ($currentPage == $nbOfPages) ? "disabled" : "" ?>">
                        <a href="<?= htmlspecialchars(ADDRESS_SITE . "recettes?page=" . ($currentPage + 1)) ?>" class="page-link">Suivante</a>
                    </li>
                </ul>
            </nav>
            </div>
            <div class="col"></div>
        </div>
    </div>



</body>


</html>