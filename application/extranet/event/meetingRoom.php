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

    <div class="text-center mt-4">
        <h1 class="lang-meeting" id="idRoom"></h1>
        <h5 id="nameRoom"></h5>
    </div>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#joinRoomModal" id="joinRoom">
        Rejoindre une salle
    </button>

    <div class="modal fade" id="joinRoomModal" tabindex="-1" aria-labelledby="joinRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinRoomModalLabel">Rejoindre une salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (!isset($idRoom)):
                        ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="idRoom" class="form-label">ID de la salle <span class="text-danger">*</span></label>
                                <input type="text" name="idRoom" class="form-control" id="idRoom" placeholder="ID de la salle" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Rejoindre la salle</button>
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
            <?php if ($profilePicture != ''): ?>
            avatar: "<?= ADDRESS_IMG_PROFIL . $profilePicture ?>",
            <?php endif; ?>
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