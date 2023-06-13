<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Tout nos évènements";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

$selectEvent = $db->prepare("SELECT * FROM events WHERE endEvent >= NOW()");
$selectEvent->execute();
$events = $selectEvent->fetchAll(PDO::FETCH_ASSOC);

$result = [];

foreach ($events as $event) {

    $selectPresta = $db->prepare("SELECT * FROM users WHERE idUser = :id");
    $selectPresta->execute(array(
        'id' => $event['idPresta']
    ));

    $presta = $selectPresta->fetch(PDO::FETCH_ASSOC);

    try {
        $start = new DateTime($event['startEvent']);
    } catch (Exception $e) {
    }
    try {
        $end = new DateTime($event['endEvent']);
    } catch (Exception $e) {
    }
    $interval = $start->diff($end);
    $duration = $interval->format('%a');

    $result[] = [
        "id" => $event['idEvent'],
        "name" => $event['name'],
        "date" => date('Y-m-d', strtotime($event['startEvent'])),
        "description" => $event['description'],
        "idPresta" => $event['idPresta'],
        "presta" => $presta['firstname'] . ' ' . $presta['lastname'],
        "duration" => $duration
    ];
}
?>

<body>

<main>

    <div class="text-center mt-4 pb-4">
        <h1>Calendrier des évènements</h1>
    </div>

    <div class="col-md-8 m-auto pt-4">
        <div id="calendar"></div>
    </div>

    <?php if (isset($_SESSION['id']) && ($_SESSION['role'] == '4' || $_SESSION['role'] == '5')): ?>
        <div class="modal fade" id="eventModalForm" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabelForm">Ajouter un événement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php include PATH_APPLICATION_EXTRANET . 'event/addEvent.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="eventName" class="form-label"><strong>Nom de l'événement :</strong> <span id="eventName" class="fs-5"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="eventDate" class="form-label"><strong>Début de l'événement :</strong> <span id="eventDate"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label"><strong>Description de l'événement :</strong> <span id="eventDescription"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="eventDuration" class="form-label"><strong>Durée de l'événement :</strong> <span id="eventDuration"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="eventPresta" class="form-label"><strong>Prestataire :</strong> <span id="eventPresta"></span></label>
                    </div>
                </div>

                <?php
                foreach ($result as $event):

                    if (isset($_SESSION['id']) && ($_SESSION['role'] == '4' || $_SESSION['role'] == '5') && $_SESSION['id'] == $event['idPresta']): ?>

                        <div class="modal-footer">
                            <a href="<?= ADDRESS_SITE ?>évènements/modifier/<?= $event['id'] ?>" class="btn btn-primary">Modifier</a>
                            <a href="<?= ADDRESS_SITE ?>évènements/supprimer/<?= $event['id'] ?>" class="btn btn-danger">Supprimer</a>
                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="<?= ADDRESS_SITE ?>ressources/js/evo-calendar.js"></script>

<script>

    let calendar = $('#calendar');

    $(document).ready(function() {
        calendar.evoCalendar({
            language: 'fr',
            theme: 'Orange Coral',
            format: 'dd MM yyyy',
            titleFormat: 'MM yyyy',
            eventHeaderFormat: 'd MM yyyy',
            firstDayOfWeek: 1,
        });

        calendar.evoCalendar('addCalendarEvent', [
            <?php foreach ($result as $event): ?>
            {
                id: '<?= $event['id'] ?>',
                name: '<?= $event['name'] ?>',
                date: '<?= $event['date'] ?>',
                description: '<?= $event['description'] ?>',
                type: 'event',
                color: '#FFD700',
            },
            <?php endforeach; ?>
        ]);

        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == '4' || $_SESSION['role'] == '5')): ?>

            calendar.on('selectDate', function(event, newDate, oldDate) {


                let date = new Date(newDate);

                let day = date.getDate();
                let month = date.getMonth() + 1;
                let year = date.getFullYear();

                if (day < 10) day = '0' + day;
                if (month < 10) month = '0' + month;
                let formattedDate = day + '-' + month + '-' + year;

                let addButton = $('<button>')
                    .attr('id', 'addEventButton')
                    .text('Ajouter un événement')
                    .on('click', function() {

                        let formAction = `<?= ADDRESS_SITE ?>évènements/ajout/${formattedDate}/verification`;
                        $('#eventForm').attr('action', formAction);

                        let datetimeValue = `${year}-${month}-${day}T08:00`;
                        $('#start').val(datetimeValue);

                        $('#eventModalForm').modal('show');

                    });

                let addEventButton = $('#addEventButton');
                let calendarEvents = $('.calendar-events');

                if(addEventButton.length === 0) {
                    calendarEvents.append(addButton);
                }else {
                    addEventButton.remove();
                    calendarEvents.append(addButton);
                }

            });

        <?php endif; ?>

        calendar.on('selectEvent', function(event, activeEvent) {

            let id = activeEvent.id;

            $.ajax({
                url: `<?= ADDRESS_SITE ?>évènements/get/${id}`,
                type: 'GET',
                success: function(data) {

                    let date = new Date(data.date);

                    let day = date.getDate();
                    let month = date.getMonth() + 1;
                    let year = date.getFullYear();

                    if (day < 10) day = '0' + day;
                    if (month < 10) month = '0' + month;
                    let formattedDate = day + '-' + month + '-' + year;

                    let datetimeValue = `${year}-${month}-${day}T08:00`;

                    $('#eventModalLabel').text('Détails de l\'événement');
                    $('#eventName').text(data.name);
                    $('#eventDate').text(formattedDate);
                    $('#eventDescription').text(data.description);
                    $('#eventDuration').text(data.duration + ' jours');
                    $('#eventPresta').text(data.presta);

                    $('#start').val(datetimeValue);

                    $('#eventModal').modal('show');

                }
            });

        });

    });

    function selectedPlace(select) {

        if(parseInt(select) === 3){

            // Ajouter ce span (<span style="color: red;">*</span>) à la fin de chaque label

            /** LABELS **/

            let labelAddress = $('<label>')
                .attr('for', 'address')
                .attr('class', 'form-label')
                .text('Adresse :');

            let labelCity = $('<label>')
                .attr('for', 'city')
                .attr('class', 'form-label')
                .text('Ville :');

            let labelZip = $('<label>')
                .attr('for', 'zip')
                .attr('class', 'form-label')
                .text('Code postal :');

            /** SPANS **/
            labelAddress.append($('<span>').attr('style', 'color: red;').text('*'));
            labelCity.append($('<span>').attr('style', 'color: red;').text('*'));
            labelZip.append($('<span>').attr('style', 'color: red;').text('*'));

            /** INPUTS **/
            let inputAddress = $('<input>')
                .attr('type', 'text')
                .attr('name', 'address')
                .attr('id', 'address')
                .attr('class', 'form-control')
                .attr('placeholder', 'Adresse')
                .attr('required', 'required');

            let inputCity = $('<input>')
                .attr('type', 'text')
                .attr('name', 'city')
                .attr('id', 'city')
                .attr('class', 'form-control')
                .attr('placeholder', 'Ville')
                .attr('required', 'required');

            let inputZip = $('<input>')
                .attr('type', 'text')
                .attr('name', 'zip')
                .attr('id', 'zip')
                .attr('class', 'form-control')
                .attr('placeholder', 'Code postal')
                .attr('required', 'required');

            /** DIVS **/
            let divAddress = $('<div>')
                .attr('class', 'mb-3');

            let divCity = $('<div>')
                .attr('class', 'mb-3');

            let divZip = $('<div>')
                .attr('class', 'mb-3');

            /** APPENDS **/
            divAddress.append(labelAddress);
            divAddress.append(inputAddress);

            divCity.append(labelCity);
            divCity.append(inputCity);

            divZip.append(labelZip);
            divZip.append(inputZip);

            $('#placeForm').append(divAddress);
            $('#placeForm').append(divCity);
            $('#placeForm').append(divZip);

        } else {

            $('#placeForm').empty();

        }

    }

</script>

</body>
