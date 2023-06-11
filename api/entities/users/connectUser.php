<?php

function connectUser($email, $password) {

    global $db;

    $password = hash('sha512', $password);

    $connectUserQuery = $db->prepare('SELECT COUNT(*) AS passwordIsCorrect FROM 
                                         users WHERE 
                                                   email = :email AND 
                                                   password = :password');

    $connectUserQuery->execute([
        'email' => $email,
        'password' => $password
    ]);

    $passwordIsCorrect = $connectUserQuery->fetch();

    if ($passwordIsCorrect['passwordIsCorrect'] == 0) {
        return false;
    }

    $connectUserQuery = $db->prepare('SELECT idUser, lastname, firstname, password, role, profilePicture FROM users WHERE email = :email GROUP BY idUser');
    $connectUserQuery->execute([
        'email' => $email
    ]);

    return $connectUserQuery->fetch(PDO::FETCH_ASSOC);

}