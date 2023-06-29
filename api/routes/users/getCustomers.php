<?php
require_once __DIR__ . "/../../libraries/response.php";
//require_once __DIR__ . "/../../entities/users/getCustomers.php";

try {
    global $db;

    header("Content-Type: application/json");

    $getCustomersQuery = $db->prepare(
        "
        SELECT lastname, firstname, email, birthdate, role, creation FROM users WHERE role > 0 ORDER BY firstname, lastname ASC;
        "
    );

    $getCustomersQuery->execute();

    $customers = $getCustomersQuery->fetchAll(PDO::FETCH_ASSOC);

    echo jsonResponse(200, [], [
        "success" => true,
        "customers" => $customers
    ]);

} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting user: " . $exception->getMessage()
    ]);
}