<?php
include PATH_SCRIPT . "functions.php";

global $db;

$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : null;
$idPresta = isset($_POST['presta']) ? htmlspecialchars($_POST['presta']) : null;
$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
$start = isset($_POST['start']) ? htmlspecialchars($_POST['start']) : null;

$errors = [];

if (empty($name)) {
    $errors['name'] = "Veuillez renseigner le nom de l'évènement";
}

if (empty($type)) {
    $errors['type'] = "Veuillez renseigner le type de l'évènement";
}

if (empty($idPresta)) {
    $errors['presta'] = "Veuillez renseigner le prestataire de l'évènement";
}

if (empty($description)) {
    $errors['description'] = "Veuillez renseigner la description de l'évènement";
}

if (empty($start)) {
    $errors['start'] = "Veuillez renseigner la date de début de l'évènement";
}

if (strtotime($start) < strtotime(date('Y-m-d'))) {
    header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=La date de début ne peut pas être dans le passé');
    exit();
}

if (strtotime($start) < strtotime(date('Y-m-d', strtotime('+7 days')))) {
    header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=L\'évènement doit être créer au minimum 7 jours avant la date de début');
    exit();
}

if($type != 4){

    $typePlace = isset($_POST['typePlace']) ? htmlspecialchars($_POST['typePlace']) : null;
    $end = isset($_POST['end']) ? htmlspecialchars($_POST['end']) : null;
    $maxParticipants = isset($_POST['max']) ? htmlspecialchars($_POST['max']) : null;

    if (empty($typePlace)) {
        $errors['typePlace'] = "Veuillez renseigner le type de lieu de l'évènement";
    }

    if (empty($end)) {
        $errors['end'] = "Veuillez renseigner la date de fin de l'évènement";
    }

    if (empty($maxParticipants)) {
        $errors['max'] = "Veuillez renseigner le nombre maximum de participants";
    }

    if (strtotime($end) < strtotime(date('Y-m-d'))) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=La date de fin ne peut pas être dans le passé');
        exit();
    }

    if (strtotime($start) > strtotime($end)) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=La date de début ne peut pas être supérieure à la date de fin');
        exit();
    }

}else{

    $dayCourse = isset($_POST['dayCourse']) ? htmlspecialchars($_POST['dayCourse']) : null;
    $imageTraining = $_FILES['imageTraining'] ?? null;

    $idMeeting = uniqid();

    if (empty($dayCourse)) {
        $errors['dayCourse'] = "Veuillez renseigner le nombre de jours de la formation";
    }

    if($imageTraining != null && $imageTraining['error'] == 0){

        $file = uploadPicture('imageTraining', $imageTraining);

        // Si file retourne un entier, c'est qu'il y a une erreur
        if(is_int($file)){
            $errors[] = "Erreur lors de l'upload de l'image";
        }else{
            $imageTraining = $file;
        }
    }

    $insertTraining = $db->prepare("INSERT INTO training_course (name, description, image, idMeeting, pathDiploma, start, nbDays, idPresta) VALUES (:name, :description, :image, :idMeeting, :pathDiploma, :start, :nbDays, :idPresta)");
    $insertTraining->execute([
        'name' => $name,
        'description' => $description,
        'image' => $imageTraining,
        'idMeeting' => $idMeeting,
        'pathDiploma' => '',
        'start' => $start,
        'nbDays' => $dayCourse,
        'idPresta' => $idPresta
    ]);

    header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=La formation a bien été créée');
    exit();

}

if (empty($errors)) {

    if($typePlace == 3){
        $idRoom = isset($_POST['room']) ? htmlspecialchars(intval($_POST['room'])) : null;
        $selectMaxCapacity = $db->prepare("SELECT capacity FROM rooms WHERE idRoom = :id");

        $selectMaxCapacity->execute([
            'id' => $idRoom
        ]);

        $maxCapacity = $selectMaxCapacity->fetch(PDO::FETCH_ASSOC);

        if($maxCapacity['capacity'] < $maxParticipants){
            header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Le nombre maximum de participants ne peut pas être supérieur à la capacité de la salle');
            exit();
        }
    }

    $addEvent = $db->prepare("INSERT INTO events (name, type, typePlace, idPresta, description, startEvent, endEvent, maxParticipant, status) VALUES (:name, :type, :typePlace, :idPresta, :description, :startEvent, :endEvent, :maxParticipant, :status)");
    $addEvent->execute([
        'name' => $name,
        'type' => $type,
        'typePlace' => $typePlace,
        'idPresta' => $idPresta,
        'description' => $description,
        'startEvent' => $start,
        'endEvent' => $end,
        'maxParticipant' => $maxParticipants,
        'status' => 1
    ]);

    $eventId = $db->lastInsertId();

    if($typePlace == 2){
        $idMeeting = uniqid();
        $addPlace = $db->prepare("UPDATE events SET idMeeting = :idMeeting WHERE idEvent = :idEvent");
        $addPlace->execute([
            'idMeeting' => $idMeeting,
            'idEvent' => $eventId
        ]);

        if ($addPlace->rowCount() > 0) {
            $success = "L'évènement a bien été ajouté";
        } else {
            $errors['add'] = "Une erreur est survenue lors de l'ajout du lieu";
        }
    }else if($typePlace == 3){
        $idRoom = isset($_POST['room']) ? htmlspecialchars(intval($_POST['room'])) : null;

        $updateEvent = $db->prepare("UPDATE events SET idRoom = :idRoom WHERE idEvent = :idEvent");
        $updateEvent->execute([
            'idRoom' => $idRoom,
            'idEvent' => $eventId
        ]);

        if ($updateEvent->rowCount() > 0) {
            $success = "L'évènement a bien été ajouté";
        } else {
            $errors['add'] = "Une erreur est survenue lors de l'ajout du lieu";
        }

        $updateRoom = $db->prepare("UPDATE rooms SET availability = 0 WHERE idRoom = :idRoom");
        $updateRoom->execute([
            'idRoom' => $idRoom
        ]);

        if ($updateRoom->rowCount() > 0) {
            $success = "L'évènement a bien été ajouté";
        } else {
            $errors['add'] = "Une erreur est survenue lors de l'ajout du lieu";
        }
    }

    if ($addEvent->rowCount() > 0) {
        $success = "L'évènement a bien été ajouté";
    } else {
        $errors['add'] = "Une erreur est survenue lors de l'ajout de l'évènement";
    }

    header("Location: " . ADDRESS_SITE . "évènements?type=success&message=" . $success);

}