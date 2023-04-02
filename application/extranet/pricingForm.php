<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cook Master - Pricing";
include '../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';

?>

<body>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">Free</th>
        <th scope="col">Starter</th>
        <th scope="col">Master</th>
    </tr>
    </thead>

    <tbody>
        <tr>
            <th scope="row">Présence de publicités dans le contenu</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
        </tr>
        <tr>
            <th scope="row">Access to the recipes</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
        </tr>
    </tbody>
</table>

</body>