<?php
if(isset($_SESSION['id']) && ($_SESSION['role'] != 4 && $_SESSION['role'] != 5)):
    header('Location: ' . ADDRESS_SITE);
    exit;
endif;
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Ajouter un cours";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';
?>

<body>

<main>

</main>

</body>
