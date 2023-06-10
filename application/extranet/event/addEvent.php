<?php
if(isset($_SESSION['role']) && ($_SESSION['role'] != 4 && $_SESSION['role'] != 5)){
    header('Location: ' . ADDRESS_SITE . 'évènements');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Ajouter un évènement";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

</main>

</body>
