<?php

require_once __DIR__ . "/../../entities/users/checkPassword.php";
require_once __DIR__ . "/../../libraries/header.php";
require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../entities/users/tokenAutoAuthentication.php";

header("Content-Type: application/json");

try {
    $token = getAuthorizationBearerToken();

    if (!$token) {
        throw new Exception("Provide an Authorization: Bearer token",401);
    }

    if (!$newToken = valid_token($token)) {
        throw new Exception("Invalid Token",401);
    }

    $body = getBody();

    if ($body === null) {
        throw new Exception("Missing body",400);
    } else {
        if (!isset($body["password"])) {
            throw new Exception("You must provide a password",400);
        }
    }

    $password = $body["password"];

    $user = checkPassword($password,$newToken['token']);

    if (!$user) {
        throw new Exception("Password incorrect",403);
    }

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "result" => "true",
        "token" => $newToken["token"]
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