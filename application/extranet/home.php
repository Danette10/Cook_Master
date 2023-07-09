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
        <?php
        $selectEvents = $db->prepare("SELECT * FROM register INNER JOIN events ON register.idEvent = events.idEvent AND events.startEvent >= :date WHERE idUser = :idUser");
        $selectEvents->execute([
            'idUser' => $_SESSION['id'],
            'date' => date('Y-m-d')
        ]);

        $events = $selectEvents->fetchAll(PDO::FETCH_ASSOC);

        if(count($events) > 0):
            ?>
            <div class="container mt-4">
                <div class="row">

                    <div class="text-center mt-4">
                        <h1>Vos évènements</h1>
                    </div>
                    <?php
                    foreach ($events as $key => $event):
                        $selectEvent = $db->prepare("SELECT * FROM events WHERE idEvent = :idEvent");
                        $selectEvent->execute([
                            'idEvent' => $event['idEvent']
                        ]);

                        $event = $selectEvent->fetch(PDO::FETCH_ASSOC);

                        $roomImage = '';

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

                            $image = ADDRESS_IMG . 'roomImage/' . $room['image'];
                            $roomImage = "<img src='$image' class='card-img-top mt-2 rounded-3' alt='Image de la salle'>";

                            endif;

                        ?>

                    <div class="card me-3" style="width: 18rem;">
                        <?= $roomImage ?>
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
        $selectAllEvents = $db->prepare("SELECT * FROM events WHERE startEvent >= :date");
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
                        $roomImage = '';
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

                            $image = $room['image'];
                            $roomImage = '<img src="' . ADDRESS_IMG . 'roomImage/' . $image . '" class="card-img-top mt-2 rounded-3" alt="Image de la salle">';

                        endif;
                        ?>

                    <div class="card me-3" style="width: 18rem;">

                        <div class="card-body">
                            <?= $roomImage ?>
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

                        </div>
                    </div>

            <div class="text-center mt-4">
                <h1>Les dernières recettes</h1>
            </div>

    <div class="container">
        <div class="row">
            <div class="card-group">
                <?php
                $selectRecipes = $db->prepare("SELECT * FROM recipe ORDER BY creationDate DESC LIMIT 5");
                $selectRecipes->execute();

                $recipes = $selectRecipes->fetchAll(PDO::FETCH_ASSOC);

                foreach($recipes as $recipe):
                    $selectIfLike = $db->prepare("SELECT count(*) as isLikes FROM likes WHERE idRecipe = :idRecipe AND idUser = :idUser");
                    $selectIfLike->execute([
                        'idRecipe' => $recipe['idRecipe'],
                        'idUser' => $_SESSION['id']
                    ]);

                    $like = $selectIfLike->fetch(PDO::FETCH_ASSOC);
                    $like = $like['isLikes'];
                    ?>
                    <div class="me-3 mt-3">
                        <div class="card board" style="width: 18rem; height: 100%;">
                            <img src="<?= ADDRESS_SITE . 'ressources/images/recipesImages/' . $recipe['recipeImage'] ?>" width="300" height="300" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h4><?= cutString($recipe['recipeName'],20) ?></h4>
                                <p class="card-text"><?= cutString($recipe['description'],55) ?></p>
                            </div>
                            <div class="m-2 d-flex justify-content-between align-items-start">
                                <p class="card-text"><small class="text-muted">Publié le <?= date('d/m/Y', strtotime($recipe['creationDate'])) ?></small></p>
                                <span id="likes_<?= $recipe['idRecipe'] ?>" class="likes" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?= $like ?> <?= ($like > 1) ? 'likes' : 'like' ?>">
                                <?php
                                if($like == 0):
                                    ?>
                                    <img src="<?= ADDRESS_IMG ?>unlike.png" width="30" height="30" alt="like" onclick="likes('recipes',<?= $recipe['idRecipe'] ?>)" style="cursor: pointer;">
                                <?php
                                else:
                                    ?>
                                    <img src="<?= ADDRESS_IMG ?>like.png" width="30" height="30" alt="like" onclick="likes('recipes',<?= $recipe['idRecipe'] ?>)" style="cursor: pointer;">
                                <?php
                                endif;
                                ?>
                                </span>
                            </div>
                            <div class="card-footer">
                                <a href="<?= ADDRESS_SITE ?>recette/<?= $recipe['idRecipe'] ?>" class="btn btn-primary">Voir la recette</a>
                            </div>
                        </div>
                    </div>

            <?php endforeach; ?>
            </div>
        </div>
    </div>

</main>

</body>

</html>
