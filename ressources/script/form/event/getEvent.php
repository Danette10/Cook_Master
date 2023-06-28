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

if($event['idRoom'] != null){
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
}else{
    $placeInfo = '';
}

$start = new DateTime($event['startEvent']);
$end = new DateTime($event['endEvent']);
$interval = $start->diff($end);
$duration = $interval->format('%a');

$selectRegister = $db->prepare("SELECT COUNT(*) as nbRegister FROM register WHERE idEvent = :id");
$selectRegister->execute(array(
    'id' => $event['idEvent']
));
$register = $selectRegister->fetch(PDO::FETCH_ASSOC);

if(isset($_SESSION['id'])):
    $selectIfRegister = $db->prepare("SELECT COUNT(*) as nbRegister FROM register WHERE idEvent = :id AND idUser = :idUser");
    $selectIfRegister->execute(array(
        'id' => $event['idEvent'],
        'idUser' => $_SESSION['id']
    ));
    $ifRegister = $selectIfRegister->fetch(PDO::FETCH_ASSOC);
    $isRegister = $ifRegister['nbRegister'];
else:
    $isRegister = 0;
endif;

$remainingPlaces = $event['maxParticipant'] - $register['nbRegister'];

echo json_encode([
    "id" => $event['idEvent'],
    "name" => $event['name'],
    "date" => date('Y-m-d', strtotime($event['startEvent'])),
    "description" => html_entity_decode($event['description']),
    "presta" => $presta['firstname'] . ' ' . $presta['lastname'],
    "duration" => $duration,
    "isRegister" => $isRegister,
    "remainingPlaces" => $remainingPlaces,
    "place" => html_entity_decode($placeInfo),
]);