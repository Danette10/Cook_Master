<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Chat";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';
?>

<body>

<main>


</main>

<script>

    // LOCAL
    const socket = new WebSocket('ws://localhost:8081');

    // PROD
    //const socket = new WebSocket('wss://cookorama.fr:9999');

    socket.onopen = function (e) {
        console.log("Connection established!");
    };

</script>

</body>

</html>