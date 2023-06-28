<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/users/getDataChartCustomers.php";

try {

    $data = getDataChartCustomers();

    if(empty($data)){

        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "No data found"
        ]);
        exit();

    }else{

        echo jsonResponse(200, [], [
            "success" => true,
            "result" => $data
        ]);

    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting data: " . $exception->getMessage()
    ]);
}