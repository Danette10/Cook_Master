<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cook Master - Home";
include 'ressources/script/head.php';
include PATH_SCRIPT . 'header.php';
?>

<body>

<?php include 'ressources/script/messages.php'; ?>

<?php
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
echo '</div>'; ?>


<?php
include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';
?>

</body>

</html>
