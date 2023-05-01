<?php
require_once ('init.php');
$type = htmlspecialchars($_POST['type']);

global $db;

switch ($type) {

    case 'deleteProfilPicture':

        $id = htmlspecialchars($_POST['id']);

        $select = $db->prepare('SELECT profilePicture FROM users WHERE idUser = :idUser');
        $select->execute(array(
            'idUser' => $id
        ));

        $profilePicture = $select->fetch();
        $profilePicture = $profilePicture['profilePicture'];

        if($profilePicture != '') {
            unlink(PATH_IMG . 'profilePicture/' . $profilePicture);
        }

        $req = $db->prepare('UPDATE users SET profilePicture = NULL WHERE idUser = :idUser');
        $req->execute(array(
            'idUser' => $id
        ));

        if($req) {

            $html = '<label>Photo de profil</label>';
            $html .= '<input type="file" class="form-control" id="profilePicture" name="profilePicture">';
            echo $html;

        } else {

            echo "error";

        }


}