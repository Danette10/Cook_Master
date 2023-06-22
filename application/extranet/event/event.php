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

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="text-center mt-4 pb-4 d-flex justify-content-center align-items-center">
        <h1 class="lang-calendar"></h1>
        <?php
        if(isset($_SESSION['role']) && ($_SESSION['role'] == 4 || $_SESSION['role'] == 5)):
            ?>
            <a href="<?= ADDRESS_SITE ?>évènements/déclarer-une-salle" class="ms-3">
                <button type="button" class="btn connexionLink shadow lang-declareRoom"></button>
            </a>
        <?php
        endif;
        ?>
    </div>

    <div class="col-md-9 m-auto pt-4">
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
                    <div id="allPlaceInfo">
                        <label for="placeInformation" class="form-label"><strong id="labelPlaceInfo"></strong> <span id="placeInformation"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="eventPresta" class="form-label"><strong>Prestataire :</strong> <span id="eventPresta"></span></label>
                    </div>
                </div>

                <div class="modal-footer" id="eventModalFooter">
                </div>

            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="<?= ADDRESS_SITE ?>ressources/js/evo-calendar.js"></script>

<script>

    let calendar = $('#calendar');

    $(document).ready(function() {
        let format = '';
        let titleFormat = '';
        let eventHeaderFormat = '';
        let language = '';

        if(localStorage.getItem('language') === 'fr') {
            language = 'fr';
            format = 'dd MM yyyy';
            titleFormat = 'MM yyyy';
            eventHeaderFormat = 'd MM yyyy';
        } else {
            language = 'en';
            format = 'MM dd yyyy';
            titleFormat = 'yyyy MM';
            eventHeaderFormat = 'MM d yyyy';
        }

        calendar.evoCalendar({
            language: language,
            theme: 'Orange Coral',
            format: format,
            titleFormat: titleFormat,
            eventHeaderFormat: eventHeaderFormat,
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

                // Vérifier que c'est pas une date passée
                let today = new Date();
                let todayDay = today.getDate();
                let todayMonth = today.getMonth() + 1;
                let todayYear = today.getFullYear();

                if (todayDay < 10) todayDay = '0' + todayDay;
                if (todayMonth < 10) todayMonth = '0' + todayMonth;
                let formattedToday = todayDay + '-' + todayMonth + '-' + todayYear;

                if (formattedDate < formattedToday) {
                    return;
                }

                let addButton = $('<button>')
                    .attr('id', 'addEventButton')
                    .addClass('lang-addEvent')
                    .on('click', function() {

                        let formAction = `<?= ADDRESS_SITE ?>évènements/ajout/${formattedDate}/verification`;
                        $('#eventForm').attr('action', formAction);

                        let datetimeValue = `${year}-${month}-${day}T08:00`;
                        $('#start').val(datetimeValue);

                        $('#eventModalForm').modal('show');

                    })

                changeLang(localStorage.getItem('language'));

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

                    if(data.linkMeeting !== null) {
                        $('#eventDescription').append('<br>Vous pouvez rejoindre la réunion en cliquant sur le lien suivant : <a href="' + data.linkMeeting + '" target="_blank">' + data.linkMeeting + '</a>');
                    }

                    $('#start').val(datetimeValue);

                    if(data.place !== null) {
                        $('#allPlaceInfo').attr('class', 'mb-3');
                        $('#labelPlaceInfo').text('Adresse de l\'événement :');
                        $('#placeInformation').text(data.place);
                    }else{
                        $('#allPlaceInfo').remove();
                    }

                    <?php if (isset($_SESSION['id']) && ($_SESSION['role'] == '4' || $_SESSION['role'] == '5') && $_SESSION['id'] == $event['idPresta']): ?>

                        let eventModalFooter = $('#eventModalFooter');

                        let modifyButton = $('<a>')
                            .attr('href', `<?= ADDRESS_SITE ?>évènements/modifier/${id}`)
                            .attr('class', 'btn btn-warning')
                            .text('Modifier');

                        let deleteButton = $('<a>')
                            .attr('href', `<?= ADDRESS_SITE ?>évènements/supprimer/${id}`)
                            .attr('class', 'btn btn-danger')
                            .text('Supprimer');

                        eventModalFooter.empty();
                        eventModalFooter.append(modifyButton);
                        eventModalFooter.append(deleteButton);

                    <?php endif; ?>

                    $('#eventModal').modal('show');

                }
            });

        });

    });

    function selectedPlace(select) {

        if(parseInt(select) === 3){

            $.ajax({
                url: `<?= ADDRESS_SCRIPT_EVENT ?>getPlace.php`,
                type: 'GET',
                success: function(data) {

                    let placeForm = $('#placeForm');
                    placeForm.empty();
                    placeForm.append(data);
                }
            });

        } else {

            $('#placeForm').empty();

        }

    }

</script>

</body>
