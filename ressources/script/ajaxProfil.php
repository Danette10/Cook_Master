<?php
require_once ('init.php');
$type = htmlspecialchars($_POST['type']);

global $db;

switch ($type) {

    case 'deleteProfilPicture':

        $id = htmlspecialchars($_POST['id']);

        $select = $db->prepare('SELECT profilePicture FROM user WHERE id = :id');
        $select->execute(array(
            'id' => $id
        ));

        $profilePicture = $select->fetch();
        $profilePicture = $profilePicture['profilePicture'];

        if($profilePicture != '') {
            unlink(PATH_IMG . 'profilePicture/' . $profilePicture);
        }

        $req = $db->prepare('UPDATE user SET profilePicture = NULL WHERE id = :id');
        $req->execute(array(
            'id' => $id
        ));

        if($req) {

            $html = '<label>Photo de profil</label>';
            $html .= '<input type="file" class="form-control" id="profilePicture" name="profilePicture">';
            echo $html;

        } else {

            echo "error";

        }


}