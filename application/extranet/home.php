<!DOCTYPE html>
<html lang="fr">
<?php

$title = "Cookorama - Home";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;
?>
<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <?php
    if(isset($_SESSION['id'])):
    ?>

        <div class="text-center mt-4">
            <h1>Vos évènements</h1>
        </div>


        <?php
        $selectEvents = $db->prepare("SELECT * FROM register WHERE idUser = :idUser");
        $selectEvents->execute([
            'idUser' => $_SESSION['id']
        ]);

        $events = $selectEvents->fetchAll(PDO::FETCH_ASSOC);

        if(count($events) > 0):
            ?>
            <div class="container mt-4">
                <div class="row">
                    <?php
                    foreach ($events as $key => $event):
                        $selectEvent = $db->prepare("SELECT * FROM events WHERE idEvent = :idEvent");
                        $selectEvent->execute([
                            'idEvent' => $event['idEvent']
                        ]);

                        $event = $selectEvent->fetch(PDO::FETCH_ASSOC);

                        if($event['typePlace'] == 3):

                            $selectRoom = $db->prepare("SELECT * FROM rooms WHERE idRoom = :idRoom");
                            $selectRoom->execute([
                                'idRoom' => $event['idRoom']
                            ]);

                            $room = $selectRoom->fetch(PDO::FETCH_ASSOC);

                            $selectPlace = $db->prepare("SELECT * FROM place WHERE idPlace = :idPlace");
                            $selectPlace->execute([
                                'idPlace' => $room['idPlace']
                            ]);

                            $place = $selectPlace->fetch(PDO::FETCH_ASSOC);

                            $roomImage = ADDRESS_IMG . 'roomImage/' . $room['image'];

                            endif;

                        ?>
                    <div class="card" style="width: 18rem;">
                        <img src="<?= $roomImage ?? '' ?>" class="card-img-top mt-2 rounded-3" alt="Image de la salle">
                        <div class="card-body">
                            <h5 class="card-title"><?= $event['name'] ?></h5>
                            <p class="card-text"><?= $event['description'] ?></p>
                            <p class="card-text">
                                Du <strong><?= date('d/m/Y', strtotime($event['startEvent'])) ?></strong>
                                au <strong><?= date('d/m/Y', strtotime($event['endEvent'])) ?></strong>
                            </p>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    else:
                        ?>
                        <div class="text-center mt-4">
                            <h3>Vous ne vous êtes inscrit à aucun évènement</h3>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>

    <?php
    else:
        $selectAllEvents = $db->prepare("SELECT * FROM events WHERE startEvent >= :date ORDER BY startEvent ASC");
        $selectAllEvents->execute([
            'date' => date('Y-m-d')
        ]);

        $events = $selectAllEvents->fetchAll(PDO::FETCH_ASSOC);

        if(count($events) > 0): ?>
            <div class="text-center mt-4">
                <h1>Les évènements à venir</h1>
            </div>

            <div class="container mt-4">
                <div class="row">
                    <?php
                    foreach ($events as $key => $value):
                        if($value['typePlace'] == 3):

                            $selectRoom = $db->prepare("SELECT * FROM rooms WHERE idRoom = :idRoom");
                            $selectRoom->execute([
                                'idRoom' => $value['idRoom']
                            ]);

                            $room = $selectRoom->fetch(PDO::FETCH_ASSOC);

                            $selectPlace = $db->prepare("SELECT * FROM place WHERE idPlace = :idPlace");
                            $selectPlace->execute([
                                'idPlace' => $room['idPlace']
                            ]);

                            $place = $selectPlace->fetch(PDO::FETCH_ASSOC);

                            $roomImage = ADDRESS_IMG . 'roomImage/' . $room['image'];

                        endif;
                        ?>

                    <div class="card" style="width: 18rem;">
                        <img src="<?= $roomImage ?? '' ?>" class="card-img-top mt-2 rounded-3" alt="Image de la salle">
                        <div class="card-body">
                            <h5 class="card-title"><?= $value['name'] ?></h5>
                            <p class="card-text"><?= $value['description'] ?></p>
                            <p class="card-text">
                                Du <strong><?= date('d/m/Y', strtotime($value['startEvent'])) ?></strong>
                                au <strong><?= date('d/m/Y', strtotime($value['endEvent'])) ?></strong>
                            </p>
                        </div>
                    </div>
                    <?php
                    endforeach;

                    else:
                        ?>
                        <div class="text-center mt-4">
                            <h3>Aucun évènement à venir</h3>
                        </div>
                    <?php
                    endif;

                    endif;
                    ?>


</main>

</body>

</html>
