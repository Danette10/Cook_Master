<?php

require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../libraries/header.php";
require_once __DIR__ . "/../../entities/users/getUser.php";

try {

    $user = getUserById($idUser);
    $token = getAuthorizationBearerToken();

    if (!$user) {
        throw new Exception("User not found", 404);
    }

    if (!$newToken = valid_token($token)) {
        throw new Exception("Invalid Token",401);
    }

    echo jsonResponse(200, [], [
        "success" => true,
        "user" => $user,
        "token" => $newToken["token"],
    ]);
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting user: " . $exception->getMessage()
    ]);
}