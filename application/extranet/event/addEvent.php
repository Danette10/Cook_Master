<?php
if(isset($_SESSION['role']) && ($_SESSION['role'] != 4 && $_SESSION['role'] != 5)){
    header('Location: ' . ADDRESS_SITE . 'évènements');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Ajouter un évènement";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

$selectPresta = $db->prepare('SELECT idUser, firstname, lastname FROM users WHERE role = 4 AND idUser != ' . $_SESSION['id'] . ' ORDER BY lastname ASC');
$selectPresta->execute();
$presta = $selectPresta->fetchAll(PDO::FETCH_ASSOC);

?>

<body>

<main>

    <div class="text-center mt-4 pb-4">
        <h1>Ajouter un évènement</h1>
    </div>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="container mt-4">

        <div class="row">

            <div class="col-3"></div>

            <div class="col-6">

                <form action="<?= ADDRESS_SITE ?>évènements/ajout/<?= $date ?>/verification" method="post">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de l'évènement <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description de l'évènement <span style="color: red;">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="start" class="form-label">Début de l'évènement <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="start" name="start" value="<?= $date ?>T08:00" required>
                    </div>

                    <div class="mb-3">
                        <label for="end" class="form-label">Fin de l'évènement <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="end" name="end" required>
                    </div>

                    <div class="mb-3">
                        <label for="max" class="form-label">Nombre maximum de participants <span style="color: red;">*</span></label>
                        <input type="number" class="form-control" id="max" name="max" required>
                    </div>

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
                        <button type="submit" class="btn btn-primary">Enregistrer l'évènement</button>
                    </div>

                </form>

            </div>

            <div class="col-3"></div>

        </div>

    </div>

</main>

</body>
