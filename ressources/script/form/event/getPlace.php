<?php
require_once '../../init.php';
global $db;

$selectRooms = $db->prepare("SELECT idRoom, name, capacity, description, idPlace FROM rooms WHERE availability = 1 ORDER BY name ASC");

$selectRooms->execute();

$rooms = $selectRooms->fetchAll(PDO::FETCH_ASSOC);

foreach ($rooms as $key => $room) {

    echo '<div class="mb-3">';
    echo '<label for="room" class="form-label">Salle disponible <span style="color: red;">*</span></label>';
    echo '<select class="form-select" id="room" name="room" required>';
    echo '<option value="0" selected>Choisir une salle</option>';
    echo '<option value="' . $room['idRoom'] . '">' . $room['name'] . ' - ' . $room['capacity'] . ' personnes</option>';
    echo '</select>';
    echo '</div>';

}