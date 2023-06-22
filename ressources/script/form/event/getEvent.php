<?php
global $db;

header('Content-Type: application/json');

$selectEvent = $db->prepare("SELECT * FROM events WHERE idEvent = :id");
$selectEvent->execute(array(
    'id' => $idEvent
));
$event = $selectEvent->fetch(PDO::FETCH_ASSOC);

$selectPresta = $db->prepare("SELECT * FROM users WHERE idUser = :id");
$selectPresta->execute(array(
    'id' => $event['idPresta']
));

$presta = $selectPresta->fetch(PDO::FETCH_ASSOC);

$selectRoom = $db->prepare("SELECT * FROM rooms WHERE idRoom = :id");
$selectRoom->execute(array(
    'id' => $event['idRoom']
));

$room = $selectRoom->fetch(PDO::FETCH_ASSOC);

$selectPlace = $db->prepare("SELECT * FROM place WHERE idPlace = :id");
$selectPlace->execute(array(
    'id' => $room['idPlace']
));

$place = $selectPlace->fetch(PDO::FETCH_ASSOC);

$placeInfo = $room['name'] . ' - ' . $place['address'] . ', ' . $place['postalCode'] . ' ' . $place['city'];

$start = new DateTime($event['startEvent']);
$end = new DateTime($event['endEvent']);
$interval = $start->diff($end);
$duration = $interval->format('%a');

echo json_encode([
    "id" => $event['idEvent'],
    "name" => $event['name'],
    "date" => date('Y-m-d', strtotime($event['startEvent'])),
    "description" => html_entity_decode($event['description']),
    "presta" => $presta['firstname'] . ' ' . $presta['lastname'],
    "duration" => $duration,
    "place" => html_entity_decode($placeInfo),
    "linkMeeting" => $event['linkMeeting'],
]);