<?php

global $db;

if($type == 'provider'){
    $role = 4;
}else{
    $role = 1;
}
$updateUser = $db->prepare('UPDATE users SET role = :role WHERE idUser = :idUser');
$updateUser->execute(array(
    'role' => $role,
    'idUser' => $idUser
));

redirectUser('/admin/dashboard/users-pending');