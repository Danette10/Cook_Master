<?php
include PATH_SCRIPT . "functions.php";

global $db;

$selectInfoTraining = $db->prepare('SELECT * FROM training_course WHERE idTrainingCourse = :idTrainingCourse');
$selectInfoTraining->execute([
    'idTrainingCourse' => $idTraining
]);

$training = $selectInfoTraining->fetch(PDO::FETCH_ASSOC);

$checkRegistration = $db->prepare('SELECT COUNT(*) as isRegistered FROM register WHERE type = 2 AND idUser = :idUser AND idEvent = :idEvent');
$checkRegistration->execute([
    'idUser' => $_SESSION['id'],
    'idEvent' => $idTraining
]);

$isRegistered = $checkRegistration->fetch(PDO::FETCH_ASSOC);

if ($isRegistered['isRegistered'] > 0) {
    $deleteRegistration = $db->prepare('DELETE FROM register WHERE type = 2 AND idUser = :idUser AND idEvent = :idEvent');
    $deleteRegistration->execute([
        'idUser' => $_SESSION['id'],
        'idEvent' => $idTraining
    ]);

    $result = $deleteRegistration->rowCount();

    if (!$result) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Une erreur est survenue lors de la désinscription à la formation');
        exit();
    }else{
        header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=Vous êtes bien désinscrit de la formation. Un mail de confirmation vous a été envoyé');
        $message = "Vôtre désinscription à la formation <strong style='font-size: 1.3em;'>" . $training['name'] . "</strong> a bien été prise en compte 🥺";
        $message .= "<br><p>Nous espérons vous voir participer à un de nos autres évènements prochainement 😊<br>A bientôt !</p>";
        $message .= "<br><p>L'équipe <em>Cookorama</em></p>";
        $message .= "<br><br><a href='" . ADDRESS_SITE . "évènements'>Retourner sur le site</a>";
        mailHtml($_SESSION['email'], "Désinscription à une formation", $message);
        exit();
    }
}else{
    $addRegistration = $db->prepare('INSERT INTO register (idUser, idEvent, type) VALUES (:idUser, :idEvent, 2)');
    $addRegistration->execute([
        'idUser' => $_SESSION['id'],
        'idEvent' => $idTraining
    ]);

    $result = $addRegistration->rowCount();

    $insertValidateTraining = $db->prepare('INSERT INTO validate_training_course (idUser, idTrainingCourse, courseRemaining) VALUES (:idUser, :idTrainingCourse, :courseRemaining)');
    $insertValidateTraining->execute([
        'idUser' => $_SESSION['id'],
        'idTrainingCourse' => $idTraining,
        'courseRemaining' => $training['nbDays']
    ]);

    if (!$result) {
        header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Une erreur est survenue lors de l\'inscription à la formation');
        exit();
    }else{
        header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=Vous êtes bien inscrit à la formation. Un mail de confirmation vous a été envoyé');
        $message = "Vôtre inscription à la formation <strong style='font-size: 1.3em;'>" . $training['name'] . "</strong> a bien été prise en compte 🥳";
        if($event['typePlace'] == 2):
            $message .= "<br>Le jour de l'évènement, vous devrez saisir le code suivant : <strong style='font-size: 1.3em;'>" . $training['idMeeting'] . "</strong> pour accéder à la salle.";
        endif;
        $message .= "<br><p>Nous espérons vous voir le <strong style='font-size: 1.3em;'>" . date('d/m/Y à H:i', strtotime($training['start'])) . "</strong> lors de cet formation 🎉🎉<br>";
        $message .= "Cette formation s'étend sur <strong style='font-size: 1.3em;'>" . $training['nbDays'] . "</strong> jours.<br>";
        $message .= "Une fois la formation terminée, vous recevrez un mail contenant votre diplôme 📜<br>";
        $message .= "A bientôt ! 😊";
        $message .= "</p>";
        $message .= "<br><p>L'équipe <em>Cookorama</em></p>";
        $message .= "<br><br><a href='" . ADDRESS_SITE . "évènements'>Retourner sur le site</a>";
        mailHtml($_SESSION['email'], "Inscription à une formation", $message);
        exit();
    }
}