<?php
include '/home/php/ressources/script/init.php';

$url = $_GET['url'] ?? '/';
$routeur = new \App\Router\Router($url);

// Get all products
$routeur->get('/products', function (){
    require PATH_API . 'routes/shop/getProducts.php';
    die();
});

// Get one product
$routeur->get('/product/:id', function ($id){
    $idProduct = htmlspecialchars($id);
    require PATH_API . 'routes/shop/getOneProduct.php';
    die();
});

// Get user by name, firstname or email
$routeur->get('/user/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/users/getUser.php';
    die();
});

// Post to connect user
$routeur->post('/user/connect', function (){
    require PATH_API . 'routes/users/connectUser.php';
    die();
});

// Get all recipes
$routeur->get('/recipes', function (){
    require PATH_API . 'routes/recipes/getRecipes.php';
    die();
});

// Get one recipe
$routeur->get('/recipe/:id', function ($id){
    $idRecipe = htmlspecialchars($id);
    require PATH_API . 'routes/recipes/getOneRecipe.php';
    die();
});

// Get recipes by search
$routeur->get('/recipes/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/recipes/getRecipesSearch.php';
    die();
});

$routeur->run();