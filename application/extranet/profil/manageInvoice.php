<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Gestion des factures";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}