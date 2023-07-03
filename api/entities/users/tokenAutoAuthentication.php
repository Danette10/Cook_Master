<?php

function valid_token(string $token) {
    global $db;

    $tokenQuery = $db->prepare('SELECT COUNT(*) AS tokenIsValid FROM users WHERE token = :token');
    $tokenQuery->execute([
        'token' => $token
    ]);

    $tokenIsValid = $tokenQuery->fetch();

    if ($tokenIsValid['tokenIsValid'] == 0) {
        return false;
    }

    $getUserMailQuery = $db->prepare('SELECT email FROM users WHERE token = :token');
    $getUserMailQuery->execute([
        'token' => $token
    ]);

    $email = $getUserMailQuery->fetch();

    $token = bin2hex(random_bytes(32));

    $updateTokenQuery = $db->prepare('UPDATE users SET token = :token WHERE email = :email');
    $updateTokenQuery->execute([
        'token' => $token,
        'email' => $email['email']
    ]);

    return [
        'token' => $token
    ];

}