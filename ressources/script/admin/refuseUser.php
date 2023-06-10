<?php

$updateUser = $db->prepare('UPDATE users SET role = 1 WHERE idUser = :idUser');
$updateUser->execute(array(
    'idUser' => $idUser
));

redirectUser();
