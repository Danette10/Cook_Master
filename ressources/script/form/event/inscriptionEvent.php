<?php
include PATH_SCRIPT . "functions.php";

global $db;

$selectInfoEvent = $db->prepare('SELECT * FROM events WHERE idEvent = :idEvent');
$selectInfoEvent->execute([
    'idEvent' => $idEvent
]);

$event = $selectInfoEvent->fetch(PDO::FETCH_ASSOC);

$checkRegistration = $db->prepare('SELECT COUNT(*) as isRegistered FROM register WHERE idUser = :idUser AND idEvent = :idEvent');
$checkRegistration->execute([
    'idUser' => $_SESSION['id'],
    'idEvent' => $idEvent
]);

$isRegistered = $checkRegistration->fetch(PDO::FETCH_ASSOC);

if ($isRegistered['isRegistered'] > 0) {
    $deleteRegistration = $db->prepare('DELETE FROM register WHERE idUser = :idUser AND idEvent = :idEvent');
    $deleteRegistration->execute([
        'idUser' => $_SESSION['id'],
        'idEvent' => $idEvent
    ]);

    $result = $deleteRegistration->rowCount();

    if (!$result) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Une erreur est survenue lors de la désinscription à l\'évènement');
        exit();
    }else{
        header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=Vous êtes bien désinscrit de l\'évènement. Vous allez recevoir un mail de confirmation');
        $message = "Vôtre désinscription à l'évènement <strong style='font-size: 1.3em;'>" . $event['name'] . "</strong> a bien été prise en compte 🥺";
        $message .= "<br><p>Nous espérons vous voir participer à un de nos autres évènements prochainement 😊<br>A bientôt !</p>";
        $message .= "<br><p>L'équipe <em>Cookorama</em></p>";
        $message .= "<br><br><a href='" . ADDRESS_SITE . "évènements'>Retourner sur le site</a>";
        mailHtml($_SESSION['email'], "Désinscription à un évènement", $message);
        exit();
    }
}else{
    $addRegistration = $db->prepare('INSERT INTO register (idUser, idEvent) VALUES (:idUser, :idEvent)');
    $addRegistration->execute([
        'idUser' => $_SESSION['id'],
        'idEvent' => $idEvent
    ]);

    $result = $addRegistration->rowCount();

    if (!$result) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Une erreur est survenue lors de l\'inscription à l\'évènement');
        exit();
    }else{
        header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=Vous êtes bien inscrit à l\'évènement. Vous allez recevoir un mail de confirmation');
        $message = "Vôtre inscription à l'évènement <strong style='font-size: 1.3em;'>" . $event['name'] . "</strong> a bien été prise en compte 🥳";
        $message .= "<br><p>Nous espérons vous voir le <strong style='font-size: 1.3em;'>" . date('d/m/Y', strtotime($event['startEvent'])) . " à " . date('H:i', strtotime($event['startEvent'])) . "</strong> lors de cet évènement 🎉🎉<br>A bientôt ! 😊</p>";
        $message .= "<br><p>L'équipe <em>Cookorama</em></p>";
        $message .= "<br><br><a href='" . ADDRESS_SITE . "évènements'>Retourner sur le site</a>";
        mailHtml($_SESSION['email'], "Inscription à un évènement", $message);
        exit();
    }
}