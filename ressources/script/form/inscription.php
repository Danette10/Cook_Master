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

    $email = htmlspecialchars(strtolower(trim($email)));
    $firstname = htmlspecialchars(ucwords(strtolower(trim($firstname))));
    $name = htmlspecialchars(strtoupper(trim($lastname)));


    $errors = [];
    //PASSWORDS CHECK
    if($password != $passwordConf){
        $errors[] = "Passwords do not match";
    }

    if( preg_match("#\d#",$password)== 0 ||
        preg_match("#[a-z]#",$password)== 0 ||
        preg_match("#[A-Z]#",$password)== 0 ||
        strlen($password) < 8
    ) {
        $errors[] = "Password doesn't meet the requiered conditons";
    }

    if (strlen($firstname)== 1 || strlen($firstname) > 40) {
        $errors[] = "Firstname has to have between 2 and 40 characters";
    }

    if (strlen($name) == 1 || strlen($name) > 100) {
        $errors[] = "Lastname has to have more than 1 character";
    }

}

if (count($errors) == 0 ) {
    echo "YOUPI";
}else {
    $_SESSION['errors'] = $errors;
    header("Location: ../../../index.php");
}