<?php
require_once '../../init.php';
global $db;

$selectRooms = $db->prepare("SELECT idRoom, name, capacity, description, idPlace FROM rooms WHERE availability = 1 ORDER BY name ASC");

$selectRooms->execute();

$rooms = $selectRooms->fetchAll(PDO::FETCH_ASSOC);

$html = '<div class="mb-3">';
$html .= '<label for="room" class="form-label">Salle disponible <span style="color: red;">*</span></label>';
$html .= '<select class="form-select" id="room" name="room" required>';
$html .= '<option value="0" selected>Choisir une salle</option>';

foreach ($rooms as $key => $room) {

    $html .= '<option value="' . $room['idRoom'] . '">' . $room['name'] . ' - ' . $room['capacity'] . ' personnes</option>';

}

$html .= '</select>';
$html .= '</div>';

echo $html;