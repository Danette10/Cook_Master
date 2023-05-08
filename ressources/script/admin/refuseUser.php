<?php

$updateUser = $db->prepare('UPDATE users SET role = 1 WHERE idUser = :idUser');
$updateUser->execute(array(
    'idUser' => $idUser
));

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
