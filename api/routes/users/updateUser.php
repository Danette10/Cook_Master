<?php

require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../libraries/header.php";
require_once __DIR__ . "/../../entities/users/tokenAutoAuthentication.php";
require_once __DIR__ . "/../../entities/users/updateUser.php";

header("Content-Type: application/json");

try {
    
    $token = getAuthorizationBearerToken();

    if (!$token) {
        throw new Exception("Provide an Authorization: Bearer token",401);
    }

    $body = getBody();

    if ($body === null) {
        throw new Exception("Missing body",400);
    } 
    
    if (!isset($body["firstname"])) {
        throw new Exception("Missing firstname",400);
    }

    $firstName = htmlspecialchars($body["firstname"]);
    
    if (strlen($firstName) < 2 && strlen($firstName) > 50) {
        throw new Exception("Firstname must be at least 2 characters and 50 characters maximum",400);
    }

    $firstName = ucfirst($firstName);

    if (!isset($body["lastname"])) {
        throw new Exception("Missing lastname",400);
    }

    $lastName = htmlspecialchars($body["lastname"]);
    
    if (strlen($lastName) < 2 && strlen($lastName) > 70) {
        throw new Exception("Lastname must be at least 2 characters and 70 characters maximum",400);
    }

    $lastName = ucfirst($lastName);

    if (!isset($body["email"])) {
        throw new Exception("Missing email",400);
    }

    $email = htmlspecialchars($body["email"]);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        throw new Exception("Email is not valid",400);
    }

    if (!isset($body["birthdate"])) {
        throw new Exception("Missing birthdate",400);
    }

    $birthDate = $body["birthdate"];

    $birthdayExplode = explode("/", $birthDate);

    if (count($birthdayExplode) !== 3) {

        $birthdayExplode = explode("-", $birthDate);

        if (count($birthdayExplode) !== 3) {
            throw new Exception("Invalid birthdate",400);
        }
        // Format YYYY-mm-dd
        $day = $birthdayExplode[2];
        $month = $birthdayExplode[1];
        $year = $birthdayExplode[0];
    }else {
        // Format mm/dd/YYYY
        $day = $birthdayExplode[0];
        $month = $birthdayExplode[1];
        $year = $birthdayExplode[2];
    }

    // Vérifier la validité de la date
    if (!checkdate($month, $day, $year)) {
        throw new Exception("Invalid birthdate", 400);
    }

    // Calculer l'âge
    $age = (new DateTime($year . '-' . $month . '-' . $day))->diff(new DateTime())->y;

    if ($age < 13 || $age > 120) {
        throw new Exception("You must be at least 13 years old and maximum 120 years old", 400);
    }

    // Réorganiser la date au format YYYY-mm-dd
    $birthDate = $year . '-' . $month . '-' . $day;

    if (!updateUser($token ,$firstName, $lastName, $email, $birthDate)) {
        throw new Exception("Error when updating user",500);
    }

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "result" => "User updated",
        "firstname" => $firstName,
        "lastname" => $lastName,
        "email" => $email,
        "birthdate" => $birthDate

    ]);

    exit();

}catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    die();
}