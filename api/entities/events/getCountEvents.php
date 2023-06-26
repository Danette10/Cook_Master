<?php

function getCountEvent(){
    global $db;
    $getCountEventQuery = $db->prepare(
        "
        SELECT COUNT(*) as nbEvent FROM events WHERE startEvent >= NOW()
        "
    );

    $getCountEventQuery->execute();
    $count = $getCountEventQuery->fetch(PDO::FETCH_ASSOC);

    return $count['nbEvent'];

}