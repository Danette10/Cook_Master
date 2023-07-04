<?php
global $db;

header('Content-Type: application/json');

if($type == 'event'):

    $label = "Détails de l'évènement";

    $type = 1;

    $selectEvent = $db->prepare("SELECT * FROM events WHERE idEvent = :id");
    $selectEvent->execute(array(
        'id' => $idEvent
    ));
    $event = $selectEvent->fetch(PDO::FETCH_ASSOC);

    $idPresta = $event['idPresta'];
    $name = $event['name'];
    $description = $event['description'];
    $date = date('d/m/y à H:i', strtotime($event['startEvent']));
    $dateToCompare = date('Y-m-d', strtotime($event['startEvent']));

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

    $remainingPlaces = $event['maxParticipant'] - $register['nbRegister'];

elseif($type == 'training'):

    $label = "Détails de la formation";

    $type = 2;

    $selectTraining = $db->prepare("SELECT * FROM training_course WHERE idTrainingCourse = :idTrainingCourse");
    $selectTraining->execute(array(
        'idTrainingCourse' => $idEvent
    ));

    $training = $selectTraining->fetch(PDO::FETCH_ASSOC);

    $name = $training['name'];
    $description = $training['description'];
    $image = $training['image'];
    $date = date('d/m/y à  H:i', strtotime($training['start']));
    $dateToCompare = date('Y-m-d', strtotime($training['start']));
    $nbDays = $training['nbDays'];
    $idPresta = $training['idPresta'];
    $duration = $training['nbDays'];

endif;





$selectPresta = $db->prepare("SELECT * FROM users WHERE idUser = :id");
$selectPresta->execute(array(
    'id' => $idPresta
));

$presta = $selectPresta->fetch(PDO::FETCH_ASSOC);

if(isset($_SESSION['id'])):
    $selectIfRegister = $db->prepare("SELECT COUNT(*) as nbRegister FROM register WHERE type = :type AND idEvent = :id AND idUser = :idUser");
    $selectIfRegister->execute(array(
        'type' => $type,
        'id' => $idEvent,
        'idUser' => $_SESSION['id']
    ));
    $ifRegister = $selectIfRegister->fetch(PDO::FETCH_ASSOC);
    $isRegister = $ifRegister['nbRegister'];
else:
    $isRegister = 0;
endif;

echo json_encode([
    "label" => $label,
    "id" => $idEvent,
    "name" => html_entity_decode($name),
    "date" => $date,
    "dateToCompare" => $dateToCompare,
    "description" => html_entity_decode($description),
    "presta" => $presta['firstname'] . ' ' . $presta['lastname'],
    "isRegister" => $isRegister,
    "duration" => $duration,
    "remainingPlaces" => $remainingPlaces ?? 0,
    "place" => isset($placeInfo) ? html_entity_decode($placeInfo) : '',
]);