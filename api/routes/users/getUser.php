<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/users/getUser.php";

try {

    $user = getUser($search);

    echo jsonResponse(200, [], [
        "success" => true,
        "user" => $user
    ]);
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting user: " . $exception->getMessage()
    ]);
}