<?php
global $db;

$selectPresta = $db->prepare('SELECT idUser, firstname, lastname FROM users WHERE role = 4 AND idUser != ' . $_SESSION['id'] . ' ORDER BY lastname ASC');
$selectPresta->execute();
$presta = $selectPresta->fetchAll(PDO::FETCH_ASSOC);

?>

<form method="post" id="eventForm">

    <div class="mb-3">
        <label for="name" class="form-label">Nom de l'évènement <span style="color: red;">*</span></label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">Type d'évènement <span style="color: red;">*</span></label>
        <select class="form-select" id="type" name="type" onchange="chooseTypeEvent(this.value)" required>
            <option value="1" selected>Atelier</option>
            <option value="2">Cours</option>
            <option value="3">Dégustation</option>
            <option value="4">Formation</option>
        </select>
    </div>

    <div id="nbDayCourse"></div>

    <div id="imageTraining"></div>

    <div class="mb-3" id="placeEvent">
        <label for="typePlace" class="form-label">Lieu de l'évènement <span style="color: red;">*</span></label>
        <select class="form-select" id="typePlace" name="typePlace" onchange="selectedPlace(this.value)" required>
      <!-- <option value="1" selected>A domicile</option> -->
            <option value="2">En ligne</option>
            <option value="3">Sur site</option>
        </select>
    </div>

    <div id="placeForm" class="mb-3"></div>

    <div class="mb-3">
        <label for="presta" class="form-label">Prestataire <span style="color: red;">*</span></label>
        <select class="form-select" id="presta" name="presta" required>
            <option value="<?= $_SESSION['id'] ?>" selected>Moi</option>
            <?php foreach ($presta as $p): ?>
                <option value="<?= $p['idUser'] ?>"><?= $p['firstname'] . ' ' . $p['lastname'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description de l'évènement <span style="color: red;">*</span></label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>

    <div class="mb-3">
        <label for="start" class="form-label">Début de l'évènement <span style="color: red;">*</span></label>
        <input type="datetime-local" class="form-control" id="start" name="start" required>
    </div>

    <div class="mb-3" id="endEvent">
        <label for="end" class="form-label">Fin de l'évènement <span style="color: red;">*</span></label>
        <input type="datetime-local" class="form-control" id="end" name="end" required>
    </div>

    <div class="mb-3" id="nbMaxParticipant">
        <label for="max" class="form-label">Nombre maximum de participants <span style="color: red;">*</span></label>
        <input type="number" class="form-control" id="max" name="max" required>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Enregistrer l'évènement</button>
    </div>

</form>