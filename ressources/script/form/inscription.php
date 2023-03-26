<?php
include "../init.php";
include "../functions.php";

$db = connectToDatabase();

if (isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConf']) && isset($_POST['birthday'])) {

    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];
    $birthday = $_POST['birthday'];

    if($password === $passwordConf){

        $password = hash('sha512', $password);

        //mailHtml($email, "Inscription", "Bonjour $name $firstname, vous êtes inscrit sur CookMaster !", "Inscription");

    }
}