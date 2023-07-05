<?php
session_start();
require_once ('init.php');
$action = htmlspecialchars($_POST['action']);

global $db;

$html = '';

switch ($action):
    case 'getPresence':
        $idTraining = htmlspecialchars($_POST['idTraining']);

        $selectPresence = $db->prepare('SELECT idUser FROM register WHERE type = 2 AND idEvent = :idEvent AND idUser != :idUser');
        $selectPresence->execute(array(
            'idEvent' => $idTraining,
            'idUser' => $_SESSION['id']
        ));
        $presences = $selectPresence->fetchAll(PDO::FETCH_ASSOC);

        $html .= '<form action="' . ADDRESS_SITE . 'évènements/présence/check" method="post" id="formPresence">';
        foreach ($presences as $presence):
            $selectUser = $db->prepare('SELECT * FROM users WHERE idUser = :idUser');
            $selectUser->execute(array(
                'idUser' => $presence['idUser']
            ));
            $user = $selectUser->fetch(PDO::FETCH_ASSOC);

            $html .= '<div class="form-check">';
            $html .= '<input class="form-check-input" type="checkbox" value="' . $user['idUser'] . '" name=presence[] id="user' . $user['idUser'] . '">';
            $html .= '<label class="form-check-label" for="user' . $user['idUser'] . '">' . $user['firstname'] . ' ' . $user['lastname'] . '</label>';
            $html .= '</div>';
        endforeach;

        $html .= '<input type="hidden" name="idTraining" value="' . $idTraining . '">';
        $html .= '<input type="submit" class="btn btn-success mt-4" value="Enregistrer" onclick="checkPresence()">';
        $html .= '</form>';

        echo $html;

        break;

endswitch;