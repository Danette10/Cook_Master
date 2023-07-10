<?php
require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../libraries/header.php";
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

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "token" => $newToken["token"],
        "id" => $newToken["id"]
    ]);
    die();
}catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    die();
}