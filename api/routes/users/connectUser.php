<?php
require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../entities/users/connectUser.php";
header("Content-Type: application/json");
$body = getBody();
if($body === null){
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Missing body"
    ]);
    exit();
}else {
    if (!isset($body["email"]) || !isset($body["password"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "You must provide an email and a password"
        ]);
        exit();
    }

}

$email = $body["email"];
$password = $body["password"];

try {

    $user = connectUser($email, $password);

    if(!$user){
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "User $email not found or password incorrect"
        ]);
    } else {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => $user
        ]);
    }
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error while connecting user $email: " . $exception->getMessage()
    ]);
}