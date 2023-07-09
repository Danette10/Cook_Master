<?php

function checkPassword($password,$token)
{
    global $db;
    
    $password = hash('sha512', $password);

    $query = $db->prepare('SELECT COUNT(*) AS passwordIsCorrect FROM 
                                         users WHERE 
                                                   token = :token AND 
                                                   password = :password');

    $query->execute([
        'token' => $token,
        'password' => $password
    ]);

    $passwordIsCorrect = $query->fetch();

    if ($passwordIsCorrect['passwordIsCorrect'] == 0) {
        return false;
    }

    return true;
}