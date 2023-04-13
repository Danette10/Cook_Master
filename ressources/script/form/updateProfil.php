<?php
session_start();
include '../init.php';
include PATH_SCRIPT . 'functions.php';

$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$newPassword = isset($_POST['newPassword']) ? htmlspecialchars($_POST['newPassword']) : '';
$confirmNewPassword = isset($_POST['confirmNewPassword']) ? htmlspecialchars($_POST['confirmNewPassword']) : '';
$lastname = isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
$firstname = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '';
$birthdate = isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : '';


if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}