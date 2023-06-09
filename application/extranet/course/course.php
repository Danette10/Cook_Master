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

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr'
        });
        calendar.setOption('locale', 'fr');
        calendar.render();
    });

</script>

</body>
