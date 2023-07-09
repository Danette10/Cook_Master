<?php

function updateUser($token,$firstName, $lastName, $email, $birthDate) {
    global $db;

    $query = $db->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, birthdate = :birthdate WHERE token = :token");
    $query->execute([
        "firstname" => $firstName,
        "lastname" => $lastName,
        "email" => $email,
        "birthdate" => $birthDate,
        "token" => $token
    ]);

    $user = $query->rowCount();

    if ($user > 0) {
        return true;
    }

    return false;

}