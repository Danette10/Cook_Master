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

    $user = $connectUserQuery->fetch();

    $token = bin2hex(random_bytes(32));

    $updateTokenQuery = $db->prepare('UPDATE users SET token = :token WHERE email = :email');
    $updateTokenQuery->execute([
        'token' => $token,
        'email' => $email
    ]);

    return [
        'token' => $token,
        'rights' => $user['role'],
        'id' => $user['idUser']
    ];

}