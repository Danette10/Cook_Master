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
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide";
    }

    if( preg_match("#\d#",$password)== 0 ||
        preg_match("#[a-z]#",$password)== 0 ||
        preg_match("#[A-Z]#",$password)== 0 ||
        strlen($password) < 8
    ) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre";
    }

    if (strlen($firstname)== 1 || strlen($firstname) > 40) {
        $errors[] = "Le prénom doit se situer entre 1 et 40 caractères";
    }

    if (strlen($name) == 1 || strlen($name) > 100) {
        $errors[] = "Le nom doit se situer entre 1 et 100 caractères";
    }

    $selectUser = $db->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
    $selectUser->execute(['email' => $email]);
    $user = $selectUser->fetchColumn();

    if ($user > 0) {
        $errors[] = "L'adresse email est déjà utilisée";
    }

    if (count($errors) == 0 ) {

        $profilePicture = "";

        if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0){

            $file = uploadProfilePicture($_FILES['profilePicture']);

            // Si file retourne un entier, c'est qu'il y a une erreur
            if(is_int($file)){
                $errors[] = "Erreur lors de l'upload de l'image";
            }else{
                $profilePicture = $file;
            }
        }

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            header("Location: " . ADDRESS_SITE . '?type=error&message=Une erreur est survenue lors de l\'inscription');
            exit();
        }



        $password = hash('sha512', $password);

        $token = bin2hex(random_bytes(64));

        $creation = date('Y-m-d H:i:s');

        $insertUser = $db->prepare("INSERT INTO user (lastname, firstname, profilePicture, email, password, role, token, fidelityCounter, birthdate, creation) VALUES (:lastname, :firstname, :profilePicture, :email, :password, :role, :token, :fidelityCounter, :birthdate, :creation)");

        $insertUser->execute(
            [
            'lastname' => $name,
            'firstname' => $firstname,
            'profilePicture' => $profilePicture,
            'email' => $email,
            'password' => $password,
            'role' => 0,
            'token' => $token,
            'fidelityCounter' => 0,
            'birthdate' => $birthday,
            'creation' => $creation
            ]
        );

        $messageMail = "<h1>Merci pour votre inscription !</h1>";
        $messageMail .= "<p>Vous pouvez activer votre compte en cliquant sur le lien ci-dessous</p>";
        $messageMail .= "<a href='" . ADDRESS_VALIDATE_INSCRIPTION . "?token=" . $token . "'>Activer mon compte</a>";
        $messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
        $messageMail .= "<p>L'équipe Cook Master</p>";

        $subject = "Cook Master - Activation de votre compte";
        $header = "Cook Master < " . MAIL . " >";
        
        mailHtml($email, $subject, $messageMail, $header);

        header("Location: " . ADDRESS_SITE . '?type=success&message=Votre inscription a bien été prise en compte, vous allez recevoir un mail pour activer votre compte');
        exit();

    }else {
        $_SESSION['errors'] = $errors;
        header("Location: " . ADDRESS_SITE);
        exit();
    }

}