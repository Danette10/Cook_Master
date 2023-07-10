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

    //had row count to verify query succes but removed it due to it retuning error when are made on user info
    // even though we only wanted to chage user pwd

    //throwing an error could send the wrong message to the user

    // it's annoyig not to check if the query was succesful but it's the best solution for now
}

function updateUserPassword($password, $token) {
    global $db;

    $query = $db->prepare("UPDATE users SET password = :password WHERE token = :token");
    $query->execute([
        "password" => $password,
        "token" => $token
    ]);

    if ($query->rowCount() === 0) {
        return false;
    }

    return true;
}