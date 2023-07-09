<?php
require_once('../init.php');

$type = htmlspecialchars($_POST['type']);
$idUser = htmlspecialchars($_POST['idUser']);
$id = htmlspecialchars($_POST['id']);

if($idUser == 0){
    return false;
}

global $db;
$html = '';

switch ($type):
    case 'recipes':
        $selectIfLiked = $db->prepare('SELECT count(*) as isLiked FROM likes WHERE idUser = :idUser AND idRecipe = :idRecipe');
        $selectIfLiked->execute([
            'idUser' => $idUser,
            'idRecipe' => $id
        ]);
        $isLiked = $selectIfLiked->fetch(PDO::FETCH_ASSOC);

        if($isLiked['isLiked'] == 0):
            $insertLike = $db->prepare('INSERT INTO likes (idUser, idRecipe) VALUES (:idUser, :idRecipe)');
            $insertLike->execute([
                'idUser' => $idUser,
                'idRecipe' => $id
            ]);
            $html .= '<img src="' . ADDRESS_IMG . 'like.png" width="30" height="30" alt="like" onclick="likes(\'recipes\',' . $id . ')" style="cursor: pointer;">';
        else:
            $deleteLike = $db->prepare('DELETE FROM likes WHERE idUser = :idUser AND idRecipe = :idRecipe');
            $deleteLike->execute([
                'idUser' => $idUser,
                'idRecipe' => $id
            ]);
            $html .= '<img src="' . ADDRESS_IMG . 'unlike.png" width="30" height="30" alt="like" onclick="likes(\'recipes\',' . $id . ')" style="cursor: pointer;">';
        endif;

        break;
    endswitch;

    echo $html;

