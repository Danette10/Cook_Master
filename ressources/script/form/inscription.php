<?php
include "../init.php";
include PATH_SCRIPT . "functions.php";

global $db;


if($_POST['typeInscription'] == '1' || $_POST['typeInscription'] == '2'){

    if (isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConf']) && isset($_POST['birthday']) && isset($_POST['adresse']) && isset($_POST['city']) && isset($_POST['postal_code'])){

        $name = $_POST['name'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConf = $_POST['passwordConf'];
        $birthday = $_POST['birthday'];
        $adresse = $_POST['adresse'];
        $city = $_POST['city'];
        $postal_code = $_POST['postal_code'];

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

        $selectUser = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $selectUser->execute(['email' => $email]);
        $user = $selectUser->fetchColumn();

        if ($user > 0) {
            $errors[] = "L'adresse email est déjà utilisée";
        }

        if (count($errors) == 0 ) {

            $profilePicture = "";

            if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0){

                $file = uploadPicture('profilePicture',$_FILES['profilePicture']);

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

            $insertUser = $db->prepare("INSERT INTO users (lastname, firstname, profilePicture, email, password, role, token, fidelityCounter, birthdate, address, city, postalCode, creation) VALUES (:lastname, :firstname, :profilePicture, :email, :password, :role, :token, :fidelityCounter, :birthdate, :address, :city, :postalCode, :creation)");

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
                    'address' => $adresse,
                    'city' => $city,
                    'postalCode' => $postal_code,
                    'creation' => $creation
                ]
            );

            if($_POST['typeInscription'] == '2'){
                $diploma = uploadFileInscription($_FILES['diploma'], 'pdf');
                $cardIdentity = uploadFileInscription($_FILES['cardId'], 'pdf');
                $updateUser = $db->prepare("UPDATE users SET diploma = :diploma, cardIdentity = :cardIdentity WHERE idUser = :id");
                $updateUser->execute([
                    'diploma' => $diploma,
                    'cardIdentity' => $cardIdentity,
                    'id' => $db->lastInsertId()
                ]);

                $messageMail = "<h1>Merci pour votre inscription !</h1>";
                $messageMail .= "<p>Votre demande pour devenir un chef de Cookorama a bien été prise en compte.</p>";
                $messageMail .= "<p>Nous vous contacterons dans les plus brefs délais pour vous informer de la suite de votre candidature.</p>";
                $messageMail .= "<p>Nous répondons en général dans un délai de 72h.</p>";
                $messageMail .= "<p>Veuillez cliquer sur le lien ci-dessous pour activer votre compte.</p>";
                $messageMail .= "<a href='" . ADDRESS_SITE . "inscription/validate/2/" . $token . "'>Activer mon compte</a>";
                $messageMail .= "<p>En attendant, vous pouvez continuer à utiliser notre site en tant que client.</p>";
                $messageMail .= "<p>L'équipe Cookorama</p>";
            }else{
                $messageMail = "<h1>Merci pour votre inscription !</h1>";
                $messageMail .= "<p>Vous pouvez activer votre compte en cliquant sur le lien ci-dessous</p>";
                $messageMail .= "<a href='" . ADDRESS_SITE . "inscription/validate/1/" . $token . "'>Activer mon compte</a>";
                $messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
                $messageMail .= "<p>L'équipe Cookorama</p>";
            }

            $subject = "Cookorama - Activation de votre compte";

            mailHtml($email, $subject, $messageMail);

            header("Location: " . ADDRESS_SITE . '?type=success&message=Votre inscription a bien été prise en compte, vous allez recevoir un email pour activer votre compte');
            exit();


        }else {
            $_SESSION['errors'] = $errors;
            header("Location: " . ADDRESS_SITE);
            exit();
        }

    }
}