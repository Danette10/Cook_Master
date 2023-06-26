<?php
require_once '/home/php/ressources/script/init.php';

$url = $_GET['url'] ?? '/';
$routeur = new \App\Router\Router($url);

// Get all products
$routeur->get('/products', function (){
    require PATH_API . 'routes/shop/getProducts.php';
});

// Get one product
$routeur->get('/product/:id', function ($id){
    $idProduct = htmlspecialchars($id);
    require PATH_API . 'routes/shop/getOneProduct.php';
});

// Get user by name, firstname or email
$routeur->get('/user/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/users/getUser.php';
});

// Post to connect user
$routeur->post('/user/connect', function (){
    require PATH_API . 'routes/users/connectUser.php';
});

// Get all customers
$routeur->get('/customers', function (){
    require PATH_API . 'routes/users/getCustomers.php';
});

// Get all recipes
$routeur->get('/recipes', function (){
    require PATH_API . 'routes/recipes/getRecipes.php';
});

// Get one recipe
$routeur->get('/recipe/:id', function ($id){
    $idRecipe = htmlspecialchars($id);
    require PATH_API . 'routes/recipes/getOneRecipe.php';
});

// Get recipes by search
$routeur->get('/recipes/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/recipes/getRecipesSearch.php';
});

// JAVA
// Get count all future events
$routeur->get('/events/count', function (){
    require PATH_API . 'routes/events/getCountEvents.php';
});


$routeur->run();