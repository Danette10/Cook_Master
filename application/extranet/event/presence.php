<?php
if (!isset($_SESSION['id']) || ($_SESSION['role'] != 4 && $_SESSION['role'] != 5)):
    header('Location: ' . ADDRESS_SITE);
    exit();
endif;
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Présence";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;
?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <?php

    $selectAllCourses = $db->prepare('SELECT * FROM training_course WHERE idPresta = :idPresta AND start > :start');
    $selectAllCourses->execute(array(
        'idPresta' => $_SESSION['id'],
        'start' => date('Y-m-d')
    ));
    $allCourses = $selectAllCourses->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="text-center mt-4">
        <h1 class="lang-presence"></h1>
    </div>

    <div class="col-md-2 m-auto">
        <div class="text-center mt-4">
            <select class="form-select" id="selectCourse" onchange="getPresence(this.value)">
                <option value="0" selected>Choisir une formation</option>
                <?php
                foreach ($allCourses as $course):
                    ?>
                    <option value="<?= $course['idTrainingCourse'] ?>"><?= $course['name'] ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </div>
    </div>

    <div id="presence" class="col-md-4 m-auto mt-4"></div>

</main>

<script>

    function checkPresence() {
        $('#formPresence').submit(function (e) {
            if (!$("input[type='checkbox']").is(':checked')) {
                e.preventDefault();
                alert('Veuillez cocher au moins une case');
            }
        });
    }

</script>
</body>
