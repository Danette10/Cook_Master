<?php

require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../libraries/header.php";
require_once __DIR__ . "/../../entities/users/tokenAutoAuthentication.php";
require_once __DIR__ . "/../../entities/users/updateUser.php";


try {
    
    $token = getAuthorizationBearerToken();

    if (!$token) {
        throw new Exception("Provide an Authorization: Bearer token",401);
    }

    $body = getBody();

    if ($body === null) {
        throw new Exception("Missing body",400);
    } 
    
    if (!$newToken = valid_token($token)) {
        throw new Exception("Invalid Token",401);
    }

    if (!isset($body["password"])) {
        throw new Exception("Missing password",400);
    }

    $password = htmlspecialchars($body["password"]);

    if (strlen($password) < 8 && strlen($password) > 50) {
        throw new Exception("Password must be at least 8 characters and 50 characters maximum",400);
    }

    $password = hash("sha512",$password);

    $user = updateUserPassword($password,$newToken['token']);

    if (!$user) {
        throw new Exception("Password not updated",403);
    }

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "password updated",
        "token" => $newToken['token']

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