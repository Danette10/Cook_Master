<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Réunion";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

if (isset($_POST['idRoom'])) {
    $idRoom = $_POST['idRoom'];
}

if(isset($idRoom)):
    $selectAllEvents = $db->prepare('SELECT * FROM events WHERE idMeeting = :idMeeting AND endEvent > NOW()');
    $selectAllEvents->execute(array(
        'idMeeting' => $idRoom
    ));
    $allEvents = $selectAllEvents->fetchAll();

    $selectAllCourses = $db->prepare('SELECT * FROM training_course WHERE idMeeting = :idMeeting');
    $selectAllCourses->execute(array(
        'idMeeting' => $idRoom
    ));
    $allCourses = $selectAllCourses->fetchAll();

    if (empty($allEvents) && empty($allCourses)) {
        header("Location: " . ADDRESS_SITE . "évènements/réunion?type=error&message=La réunion n'existe pas");
        exit();
    }
endif;

?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="text-center mt-4 d-flex justify-content-center align-items-center">
        <h1 class="lang-meeting me-3" id="idRoom"></h1>
        <button type="button"
                class="btn btn-primary lang-joinRoom"
                data-bs-toggle="modal"
                data-bs-target="#joinRoomModal"
                id="joinRoom">
        </button>
    </div>

    <div class="text-center mt-4">
        <h5 id="nameRoom"></h5>
    </div>

    <div class="modal fade" id="joinRoomModal" tabindex="-1" aria-labelledby="joinRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title lang-joinRoom" id="joinRoomModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (!isset($idRoom)):
                        ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="idRoom" class="form-label"><span class="lang-spanIdRoom"></span> <span class="text-danger">*</span></label>
                                <input type="text" name="idRoom" class="form-control lang-placeholder-IdRoom" id="idRoom" required>
                            </div>
                            <button type="submit" class="btn btn-primary lang-joinRoom"></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="meet" class="d-flex justify-content-center mt-4"></div>

</main>

<script src='https://meet.jit.si/external_api.js'></script>
<script>
    <?php if (isset($idRoom)) : ?>
    let domain = "meet.jit.si";
    let options = {
        roomName: "<?= $idRoom; ?>",
        width: 900,
        height: 600,
        userInfo: {
            email: "<?= $_SESSION['email']; ?>",
            displayName: "<?= $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>",
        },
        configOverwrite: {
            disableProfile: true,
        },
        parentNode: document.querySelector('#meet')
    };
let api = new JitsiMeetExternalAPI(domain, options);

$('#joinRoom').css('display', 'none');

$('#nameRoom').html('Réunion n°<?= $idRoom; ?>');

api.addEventListener('videoConferenceLeft', function(event) {
window.location.href = "<?= ADDRESS_SITE; ?>évènements/réunion";
});
<?php endif; ?>
</script>

</body>