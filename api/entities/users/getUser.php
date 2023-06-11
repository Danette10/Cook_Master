<?php

function getUser($search){
    global $db;
    $getUserQuery = $db->prepare(
        "
        SELECT * FROM users WHERE 
                                role > 0 AND
                                firstname LIKE '%$search%' OR 
                                lastname LIKE '%$search%' OR 
                                email LIKE '%$search%' OR
                                idUser = '$search'
                            ORDER BY firstname, lastname ASC;
        "
    );


    $getUserQuery->execute();

    return $getUserQuery->fetchAll(PDO::FETCH_ASSOC);
}