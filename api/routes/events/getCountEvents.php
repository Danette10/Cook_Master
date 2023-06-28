<?php
require_once __DIR__ . "/../../libraries/response.php";
require_once __DIR__ . "/../../entities/events/getCountEvents.php";

try {

    $nbEvents = getCountEvent();

    if(empty($nbEvents)){

        echo jsonResponse(404, [], [
            "success" => false,
            "message" => "No events found"
        ]);
        exit();

    }else{

        echo jsonResponse(200, [], [
            "success" => true,
            "result" => $nbEvents
        ]);

    }
} catch (PDOException $exception) {
    echo jsonResponse(500, [], [
        "success" => false,
        "message" => "Error while getting events: " . $exception->getMessage()
    ]);
}