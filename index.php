<?php
session_start();
include 'ressources/script/init.php';
require 'vendor/autoload.php';

$url = isset($_GET['url']) ? $_GET['url'] : '/';

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

// Routes -> Leçons
$routeur->get('/leçons', function (){
    require PATH_APPLICATION_EXTRANET . 'lesson/lesson.php';
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

// Execution du routeur
$routeur->run();

include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';

