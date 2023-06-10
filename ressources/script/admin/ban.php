<?php
global $db;

$selectRoleUser = $db->prepare('SELECT role FROM users WHERE idUser = :idUser');
$selectRoleUser->execute(array(
    'idUser' => $idUser
));
$roleUser = $selectRoleUser->fetch();

if ($roleUser['role'] == -2){
    $updateRoleUser = $db->prepare('UPDATE users SET role = 1 WHERE idUser = :idUser');
}else{
    $updateRoleUser = $db->prepare('UPDATE users SET role = -2 WHERE idUser = :idUser');
}

$updateRoleUser->execute(array(
    'idUser' => $idUser
));

redirectUser();
