<?php

include 'ressources/script/head.php';

if (!isset($_SESSION['id']) && $_SESSION['role'] != '5') {
    header('Location: ' . ADDRESS_SITE.'recettes');
    exit();
}
require_once PATH_SCRIPT . 'header.php';

global $db;

if (!isset($_POST['idRecipe']) && !isset($_POST['reason'])) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}

$idRecipe = $_POST['idRecipe'];
$reason = ucfirst(htmlspecialchars($_POST['reason']));

$getRecipeOwner = $db->prepare('SELECT idUser FROM recipe WHERE idRecipe = :idRecipe');
$getRecipeOwner->execute(array(
    'idRecipe' => $idRecipe
));
$recipeOwner = $getRecipeOwner->fetch(PDO::FETCH_ASSOC);



$deleteRecipeSteps = $db->prepare('DELETE FROM recipe_steps WHERE idRecipe = :idRecipe');
$deleteRecipeSteps->execute(array(
    'idRecipe' => $idRecipe
));

$deleteRecipeIngredients = $db->prepare('DELETE FROM recipe_ingredients WHERE idRecipe = :idRecipe');
$deleteRecipeIngredients->execute(array(
    'idRecipe' => $idRecipe
));

$deleteRecipe = $db->prepare('DELETE FROM recipe WHERE idRecipe = :idRecipe');
$deleteRecipe->execute(array(
    'idRecipe' => $idRecipe
));

if (!$recipeOwner) {
    $error = [];
    $error = "Erreur lors de la suppression de la recette, créateur introuvable.";
    header('Location: ' . ADDRESS_SITE);
    exit();
}else {
    $messageMail = "<h1>Nous vous informons que votre rectte a été supprimé par un administrateur</h1>";
    $messageMail .= "<b><p>Raison :</p></b>";
    $messageMail .= "<p>" . $reason . "</p>";
    $messageMail .= "<p>Si vous pensez que cette suppression est injustifiée, veuillez contacter un administrateur.</p>";
    $messageMail .= "<p>Cordialement, l'équipe Cookorama.</p>";

    $getUserMail = $db->prepare('SELECT email FROM users WHERE idUser = :idUser');
    $getUserMail->execute(array(
        'idUser' => $recipeOwner['idUser']
    ));
    $userMail = $getUserMail->fetch(PDO::FETCH_ASSOC);

    $to = $userMail['email'];
    $subject = "Suppression de votre recette";

    mailHtml($to, $subject, $messageMail);

    header("Location: " . ADDRESS_SITE . 'recettes?type=success&message=La recette a été supprimée avec succès.');
    exit();

}
