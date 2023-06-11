<?php
include PATH_SCRIPT . "functions.php";

global $db;

$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : null;
$typePlace = isset($_POST['typePlace']) ? htmlspecialchars($_POST['typePlace']) : null;
$idPresta = isset($_POST['presta']) ? htmlspecialchars($_POST['presta']) : null;
$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
$start = isset($_POST['start']) ? htmlspecialchars($_POST['start']) : null;
$end = isset($_POST['end']) ? htmlspecialchars($_POST['end']) : null;
$maxParticipants = isset($_POST['max']) ? htmlspecialchars($_POST['max']) : null;

$errors = [];

if (empty($name)) {
    $errors['name'] = "Veuillez renseigner le nom de l'évènement";
}

if (empty($type)) {
    $errors['type'] = "Veuillez renseigner le type de l'évènement";
}

if (empty($typePlace)) {
    $errors['typePlace'] = "Veuillez renseigner le type de lieu de l'évènement";
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

if (empty($end)) {
    $errors['end'] = "Veuillez renseigner la date de fin de l'évènement";
}

if (empty($maxParticipants)) {
    $errors['max'] = "Veuillez renseigner le nombre maximum de participants";
}

if (empty($errors)) {
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

    if ($addEvent->rowCount() > 0) {
        $success = "L'évènement a bien été ajouté";
    } else {
        $errors['add'] = "Une erreur est survenue lors de l'ajout de l'évènement";
    }

    header("Location: " . ADDRESS_SITE . "évènements?type=success&message=" . $success);

}