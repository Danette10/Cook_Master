<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Tout les utilisateurs";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 5) {
    header('Location: ' . ADDRESS_SITE);
    exit();
}

$selectUsers = $db->prepare("SELECT * FROM users WHERE role != 0 AND idUser != :idUser");
$selectUsers->execute([
    'idUser' => $_SESSION['id']
]);

$allUsers = $selectUsers->fetchAll();

?>

<body>

<main>

    <h1 class="text-center mt-3 mb-3">Tout les utilisateurs</h1>

    <?php include PATH_SCRIPT . 'messages.php'; ?>


    <div class="p-3">

        <table class="table text-center table-bordered" id="active">
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
            <?php foreach ($allUsers as $user) { ?>

                <tr>
                    <th scope="row"><?= $user['idUser'] ?></th>
                    <td><?= $user['lastname'] ?></td>
                    <td><?= $user['firstname'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['creation'] ?></td>
                    <td>
                        <div class="button_profil">
                            <?php if ($user['role'] != -2) { ?>
                                <a href="<?= ADDRESS_SITE ?>dashboard/admin/users/ban/<?= $user['idUser'] ?>" class="btn btn-danger">Bannir</a>
                            <?php } else { ?>
                                <a href="<?= ADDRESS_SITE ?>dashboard/admin/users/unban/<?= $user['idUser'] ?>" class="btn btn-success">Débannir</a>
                            <?php } ?>

                            <?php if ($user['role'] == 1) { ?>
                                <a href="<?= ADDRESS_SITE ?>dashboard/admin/users/upgrade/<?= $user['idUser'] ?>" class="btn btn-secondary">Admin</a>
                            <?php }else if ($user['role'] == 5) { ?>
                                <a href="<?= ADDRESS_SITE ?>dashboard/admin/users/downgrade/<?= $user['idUser'] ?>" class="btn btn-secondary">Utilisateur</a>
                            <?php } ?>

                            <a href="<?= ADDRESS_SITE ?>dashboard/admin/users/view/<?= $user['idUser'] ?>" class="btn btn-primary">Voir</a>
                        </div>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>

    </div>

</main>

</body>

</html>
