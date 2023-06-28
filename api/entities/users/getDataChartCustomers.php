<?php

function getDataChartCustomers(){

    global $db;
    $nbFree = 0;
    $nbStarter = 0;
    $nbMaster = 0;

    $getDataChartCustomersQuery = $db->prepare(
        "SELECT * FROM users WHERE role = 1 OR role = 2 OR role = 3");
    $getDataChartCustomersQuery->execute();

    $result = $getDataChartCustomersQuery->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $row){
        if($row['role'] == 1){
            $nbFree++;
        }
        if($row['role'] == 2){
            $nbStarter++;
        }
        if($row['role'] == 3){
            $nbMaster++;
        }
    }

    $data = array(
        "Free" => $nbFree,
        "Starter" => $nbStarter,
        "Master" => $nbMaster
    );

    return $data;
}