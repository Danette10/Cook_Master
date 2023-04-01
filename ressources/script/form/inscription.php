<?php
include "../init.php";
include PATH_SCRIPT . "functions.php";
include PATH_SCRIPT . "connectDB.php";


if (isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConf']) && isset($_POST['birthday'])) {

    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];
    $birthday = $_POST['birthday'];

    $email = htmlspecialchars(strtolower(trim($email)));
    $firstname = htmlspecialchars(ucwords(strtolower(trim($firstname))));
    $name = htmlspecialchars(strtoupper(trim($name)));


    $errors = [];
    //PASSWORDS CHECK
    if($password != $passwordConf){
        $errors[] = "Passwords do not match";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
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

    $selectUser = $db->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
    $selectUser->execute(['email' => $email]);
    $user = $selectUser->fetchColumn();

    if ($user > 0) {
        $errors[] = "Email already exists";
    }

    if (count($errors) == 0 ) {

        $password = hash('sha512', $password);

        $token = bin2hex(random_bytes(64));

        $creation = date('Y-m-d H:i:s');

        $insertUser = $db->prepare("INSERT INTO user (lastname, firstname, email, password, role, token, fidelityCounter, birthdate, creation) VALUES (:lastname, :firstname, :email, :password, :role, :token, :fidelityCounter, :birthdate, :creation)");

        $insertUser->execute(
            [
            'lastname' => $name,
            'firstname' => $firstname,
            'email' => $email,
            'password' => $password,
            'role' => 0,
            'token' => $token,
            'fidelityCounter' => 0,
            'birthdate' => $birthday,
            'creation' => $creation
            ]
        );

        $messageMail = "<h1>Thank you for your registration !</h1>";
        $messageMail .= "<p>Click on the link below to activate your account</p>";
        $messageMail .= "<a href='" . ADDRESS_VALIDATE_INSCRIPTION . "?token=" . $token . "'>Activate your account</a>";
        $messageMail .= "<p>We hope you will enjoy our services !</p>";
        $messageMail .= "<p>Cook Master Team</p>";

        $subject = "Cook Master - Activate your account";
        $header = "Cook Master < " . MAIL . " >";
        
        mailHtml($email, $subject, $messageMail, $header);

        header("Location: " . ADDRESS_SITE . '?type=success&message=You have been registered successfully ! Please check your email to activate your account.');
        exit();

    }else {
        $_SESSION['errors'] = $errors;
        header("Location: " . ADDRESS_SITE);
        exit();
    }

}