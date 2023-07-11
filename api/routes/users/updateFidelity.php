<?php


require_once __DIR__ . "/../../libraries/body.php";
require_once __DIR__ . "/../../libraries/header.php";
require_once __DIR__ . "/../../entities/users/tokenAutoAuthentication.php";
require_once __DIR__ . "/../../entities/users/updateUser.php";


header("Content-Type: application/json");
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

    if (!array_key_exists("fidelity", $body)) {
        throw new Exception("Missing fidelity", 400);
    }    

    $fidelity = intval($body["fidelity"]);
 
    if (!updateFidelity($newToken['token'],$fidelity)){
        throw new Exception("Fidelity not updated",403);
    } 


    http_response_code(200);
    echo json_encode([
        "success" => true,
        "token" => $newToken['token']
    ]);

    die();

}catch (Exception $e) {
    $responseCode = $e->getCode();
    http_response_code($responseCode);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    die();
}
