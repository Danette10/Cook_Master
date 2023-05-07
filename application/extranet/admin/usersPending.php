<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Utilisateurs en attente";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 5) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}

$selectUsersProvider = $db->prepare("SELECT * FROM users WHERE role = -1");
$selectUsersProvider->execute();
$usersProvider = $selectUsersProvider->fetchAll();

?>

<body>

<main>



    <h1 class="text-center mt-3 mb-3">Utilisateurs en attente</h1>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="p-3">

        <h3 class="mt-3 mb-3">Prestataires</h3>

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Email</th>
                <th scope="col">Date d'inscription</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($usersProvider as $userProvider) { ?>

                <tr>
                    <th scope="row"><?= $userProvider['idUser'] ?></th>
                    <td><?= $userProvider['lastname'] ?></td>
                    <td><?= $userProvider['firstname'] ?></td>
                    <td><?= $userProvider['email'] ?></td>
                    <td><?= $userProvider['creation'] ?></td>
                    <td>
                        <a href="<?= ADDRESS_SITE ?>admin/usersPending.php?download=<?= $userProvider['idUser'] ?>" class="btn btn-primary">Justificatifs</a>
                        <a href="<?= ADDRESS_SITE ?>admin/usersPending.php?accept=<?= $userProvider['idUser'] ?>" class="btn btn-success">Accepter</a>
                        <a href="<?= ADDRESS_SITE ?>admin/usersPending.php?refuse=<?= $userProvider['idUser'] ?>" class="btn btn-danger">Refuser</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>

</main>

</body>

</html>
