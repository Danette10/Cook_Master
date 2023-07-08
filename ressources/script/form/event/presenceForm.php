<?php
include PATH_SCRIPT . "functions.php";

global $db;

$arrayUserAlreadyPresent = [];

$updateValidatePresence = $db->prepare('UPDATE validate_training_course
SET courseRemaining = courseRemaining - 1 AND  courseRemaining >= 0
WHERE idUser = :idUser
  AND idTrainingCourse = :idTrainingCourse');

foreach ($_POST['presence'] as $idPresence) {
    if(!in_array($idPresence, $arrayUserAlreadyPresent)){
        $updateValidatePresence->execute(array(
            'idUser' => $idPresence,
            'idTrainingCourse' => $_POST['idTraining']
        ));
        array_push($arrayUserAlreadyPresent, $idPresence);
    }
}

if($updateValidatePresence->rowCount() > 0){

    foreach ($_POST['presence'] as $idPresence):
        $selectCourseRemaining = $db->prepare('SELECT courseRemaining FROM validate_training_course WHERE idUser = :idUser AND idTrainingCourse = :idTrainingCourse');
        $selectCourseRemaining->execute(array(
            'idUser' => $idPresence,
            'idTrainingCourse' => $_POST['idTraining']
        ));
        $courseRemaining = $selectCourseRemaining->fetch(PDO::FETCH_ASSOC);

        if($courseRemaining['courseRemaining'] == 0){
            $deletePresence = $db->prepare('DELETE FROM validate_training_course WHERE idUser = :idUser AND idTrainingCourse = :idTrainingCourse');
            $deletePresence->execute(array(
                'idUser' => $idPresence,
                'idTrainingCourse' => $_POST['idTraining']
            ));

            $selectNameFirstname = $db->prepare('SELECT lastname, firstname, email FROM users WHERE idUser = :idUser');
            $selectNameFirstname->execute(array(
                'idUser' => $idPresence
            ));
            $nameFirstname = $selectNameFirstname->fetch(PDO::FETCH_ASSOC);

            $selectTraining = $db->prepare('SELECT name FROM training_course WHERE idTrainingCourse = :idTrainingCourse');
            $selectTraining->execute(array(
                'idTrainingCourse' => $_POST['idTraining']
            ));
            $nameTraining = $selectTraining->fetch(PDO::FETCH_ASSOC);

            $nameUser = $nameFirstname['lastname'] . ' ' . $nameFirstname['firstname'];
            $file = generateDiploma($nameUser, $nameTraining['name']);
            $updateDiploma = $db->prepare('UPDATE validate_training_course SET pathDiploma = :diploma WHERE idUser = :idUser AND idTrainingCourse = :idTrainingCourse');
            $updateDiploma->execute(array(
                'diploma' => $file,
                'idUser' => $idPresence,
                'idTrainingCourse' => $_POST['idTraining']
            ));
            $file = PATH_DIPLOMAS . $file;
            $message = "Bonjour " . $nameUser . ",<br><br>";
            $message .= "🥳🥳 Félicitations, vous avez validé la formation <strong>'" . $nameTraining['name'] . "'</strong> 🥳🥳<br><br>";
            $message .= "Vous trouverez ci-joint votre attestation de formation.<br><br>";
            $message .= "Au plaisir de vous revoir sur une de nos autres formations 😊<br><br>";
            $message .= "A très bientôt,<br><br>";
            $message .= "Cordialement,<br><br>";
            $message .= "L'équipe COOKORAMA";
            mailHtml($nameFirstname['email'], 'Attestation de formation', $message, $file);
        }
    endforeach;
    header('Location: ' . ADDRESS_SITE . 'évènements?type=success&message=La liste de présence a bien été enregistrée');
    exit();
}else{
    header('Location: ' . ADDRESS_SITE . 'évènements?type=error&message=Une erreur est survenue lors de l\'enregistrement de la liste de présence');
    exit();
}