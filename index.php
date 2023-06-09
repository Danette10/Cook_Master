
<?php
session_start();
include 'ressources/script/init.php';
require 'vendor/autoload.php';

$url = $_GET['url'] ?? '/';

$routeur = new \App\Router\Router($url);

// Route -> Accueil
$routeur->get('/', function (){
    require PATH_APPLICATION_EXTRANET . 'index.php';
});

// Routes -> Inscription
$routeur->get('/inscription', function (){
    require PATH_APPLICATION_EXTRANET . 'inscriptionForm.php';
});
$routeur->get('/inscription/validate/:typeInscription/:token', function ($typeInscription, $token){
    $token = htmlspecialchars($token);
    $typeInscription = htmlspecialchars($typeInscription);
    require PATH_VALIDATE_INSCRIPTION;
});

// Routes -> Abonnement
$routeur->get('/subscribe', function (){
    require PATH_APPLICATION_EXTRANET . 'pricingForm.php';
});
$routeur->get('/subscribe/:subscription', function ($subscription){
    $subscriptionType = htmlspecialchars($subscription);
    require PATH_PAIEMENT_FORM . 'paiementChoosePlan.php';
});
$routeur->get('/subscribe/:subscription/:plan', function ($subscription, $plan){
    $subscriptionType = htmlspecialchars($subscription);
    $planType = htmlspecialchars($plan);
    require PATH_PAIEMENT_FORM . 'paiementForm.php';
});
$routeur->post('/:subscription/:plan/paiement', function ($subscription, $plan){
    $subscriptionType = htmlspecialchars($subscription);
    $planType = htmlspecialchars($plan);
    require PATH_PAIEMENT_SCRIPT . 'paiement.php';
});
$routeur->get('/confirm/:confirm/:subscription/:plan', function ($confirm, $subscription, $plan){
    $subscriptionType = htmlspecialchars($subscription);
    $planType = htmlspecialchars($plan);
    $confirm = htmlspecialchars($confirm);
    require PATH_PAIEMENT_SCRIPT . 'confirmation.php';
});

// Routes -> Profil
$routeur->get('/profil', function (){
    require PATH_APPLICATION_EXTRANET . 'profil/profil.php';
});
$routeur->get('/profil/modify/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_APPLICATION_EXTRANET . 'profil/modifyProfil.php';
});
$routeur->post('/profil/update', function (){
    require PATH_FORM . 'updateProfil.php';
});
$routeur->get('/profil/resetPassword', function (){
    $token = '';
    $email = '';
    require PATH_RESET_PASSWORD_FORM;
});
$routeur->get('/profil/resetPassword/:token/:email', function ($token, $email){
    $token = htmlspecialchars($token);
    $email = htmlspecialchars($email);
    require PATH_RESET_PASSWORD_FORM;
});
$routeur->get('/profil/manage/subscription', function (){
    require PATH_APPLICATION_EXTRANET . 'profil/manageSubscription.php';
});
$routeur->get('/profil/manage/subscription/:subscription/cancel', function ($subscription){
    $subscriptionId = htmlspecialchars($subscription);
    require PATH_SCRIPT_PROFIL . 'cancelSubscription.php';
});
$routeur->get('/profil/manage/invoice', function (){
    require PATH_APPLICATION_EXTRANET . 'profil/manageInvoice.php';
});

// Routes -> Recettes
$routeur->get('/recettes', function (){
    require PATH_APPLICATION_EXTRANET . 'recipe/recipe.php';
});
$routeur->get('/recettes/creation', function (){
    require PATH_APPLICATION_EXTRANET . 'recipe/recipeCreation.php';
});
$routeur->post('/recettes/creation/check', function (){
    require PATH_FORM . 'recipeForm.php';
});


// Routes -> Cours
$routeur->get('/cours', function (){
    require PATH_APPLICATION_EXTRANET . 'course/course.php';
});
$routeur->get('/cours/add/:date', function ($date){
    $date = htmlspecialchars($date);
    require PATH_APPLICATION_EXTRANET . 'course/addCourse.php';
});

// Routes -> Boutique
$routeur->get('/boutique', function (){
    require PATH_APPLICATION_EXTRANET . 'shop/shop.php';
});
$routeur->get('/boutique/ajout-produit', function (){
    require PATH_APPLICATION_EXTRANET . 'shop/addProduct.php';
});
$routeur->post('/boutique/ajout-produit/check', function (){
    require PATH_FORM . 'shop/addProductForm.php';
});
$routeur->get('/boutique/produit/:id', function ($id){
    $idProduct = htmlspecialchars($id);
    require PATH_APPLICATION_EXTRANET . 'shop/product.php';
});
$routeur->get('/boutique/ajout-panier/:id', function ($id){
    $idProduct = htmlspecialchars($id);
    require PATH_SCRIPT_CART . 'addProduct.php';
});

// Routes -> Panier
$routeur->get('/panier', function (){
    require PATH_APPLICATION_EXTRANET . 'cart/cart.php';
});


// Routes -> Dashboard Admin
$routeur->get('/admin/dashboard', function (){
    require PATH_APPLICATION_EXTRANET . 'admin/dashboard.php';
});

// Routes -> Dashboard Admin -> Utilisateurs
$routeur->get('/dashboard/admin/users', function (){
    require PATH_APPLICATION_EXTRANET . 'admin/users.php';
});
$routeur->get('/dashboard/admin/users-pending', function (){
    require PATH_APPLICATION_EXTRANET . 'admin/usersPending.php';
});

// Routes -> Dashboard Admin -> Actions Utilisateurs
$routeur->get('/dashboard/admin/users/ban/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'ban.php';
});
$routeur->get('/dashboard/admin/users/unban/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'ban.php';
});
$routeur->get('/dashboard/admin/users/upgrade/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'upgrade.php';
});
$routeur->get('/dashboard/admin/users/downgrade/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'upgrade.php';
});
$routeur->get('/dashboard/admin/users/view/:id', function ($id){
    $idUser = htmlspecialchars($id);
    require PATH_APPLICATION_EXTRANET . 'profil/profil.php';
});

// Routes -> Dashboard Admin -> Users Pending
$routeur->get('dashboard/admin/users-pending/:type/validate/:id', function ($type, $id){
    $type = htmlspecialchars($type);
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'validateUser.php';
});
$routeur->get('dashboard/admin/users-pending/:type/refuse/:id', function ($type, $id){
    $type = htmlspecialchars($type);
    $idUser = htmlspecialchars($id);
    require PATH_ADMIN_SCRIPT . 'refuseUser.php';
});



// API
// Get all products
$routeur->get('/api/products', function (){
    require PATH_API . 'routes/shop/getProducts.php';
    die();
});

// Get one product
$routeur->get('/api/product/:id', function ($id){
    $idProduct = htmlspecialchars($id);
    require PATH_API . 'routes/shop/getOneProduct.php';
    die();
});

// Get user by name, firstname or email
$routeur->get('/api/user/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/users/getUser.php';
    die();
});

// Post to connect user
$routeur->post('/api/user/connect', function (){
    require PATH_API . 'routes/users/connectUser.php';
    die();
});

// Get all recipes
$routeur->get('/api/recipes', function (){
    require PATH_API . 'routes/recipes/getRecipes.php';
    die();
});

// Get one recipe
$routeur->get('/api/recipe/:id', function ($id){
    $idRecipe = htmlspecialchars($id);
    require PATH_API . 'routes/recipes/getOneRecipe.php';
    die();
});

// Get recipes by search
$routeur->get('/api/recipes/:search', function ($search){
    $search = htmlspecialchars($search);
    require PATH_API . 'routes/recipes/getRecipesSearch.php';
    die();
});

// Execution du routeur
$routeur->run();

include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';

