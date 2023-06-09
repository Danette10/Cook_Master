<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Tout nos cours";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

?>

<body>

<main>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

</main>

<script>

    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr'
    });
    calendar.setOption('locale', 'fr');
    calendar.render();

    calendar.on('dateClick', function(info) {
        <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == '4' || $_SESSION['role'] == '5')): ?>
        // On peux pas cliquer sur les jours passés
        if(info.dateStr < new Date().toISOString().slice(0, 10)) {
            alert('Vous ne pouvez pas créer de cours dans le passé !');
        }else{
            window.location.href = '<?= ADDRESS_SITE ?>cours/add/' + info.dateStr;
        }
        <?php endif; ?>
    });

</script>

</body>
