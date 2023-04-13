<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php
    $title = "Cookorama - Modification du profil";
    include '../../../ressources/script/head.php';
    include PATH_SCRIPT . 'header.php';

    if (!isset($_SESSION['id'])) {
        header('Location: ' . PATH_SITE);
        exit();
    }

    $selectInfo = $db->prepare('SELECT id, profilePicture, password, birthdate FROM user WHERE id = :id');
    $selectInfo->execute(array(
        'id' => $_SESSION['id']
    ));

    $infos = $selectInfo->fetch();

    $id = $infos['id'];

    $birthdate = date('Y-m-d', strtotime($infos['birthdate']));
    $profilePicture = ADDRESS_IMG_PROFIL . $infos['profilePicture'];
    $password = $infos['password'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $email = $_SESSION['email'];

    if($id == $_SESSION['id']) {
        $titlePage = "Modification de votre profil";
    } else {
        $titlePage = "Modification du profil de " . $firstname . " " . $lastname;
    }

    ?>

    <body>

        <main>

            <h2 class="text-center mt-4"><?= $titlePage ?></h2>

            <form action="<?= ADDRESS_FORM ?>updateProfil.php" method="post" class="col-md-6" id="inscriptionForm" enctype="multipart/form-data" style="margin: 0 auto; padding: 15px;">

                <div class="mb-3">
                    <img src="<?= $profilePicture ?>" alt="Photo de profil" width="300" height="300" style="border-radius: 15px;">
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $lastname ?>" required>
                </div>

                <div class="mb-3">
                    <label for="firstname" class="form-label">Prénom *</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $firstname ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="emailInscription" name="email" value="<?= $email ?>" required>
                </div>

                <div class="mb-3">
                    <label for="birthdate" class="form-label">Date de naissance *</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= $birthdate ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword">

                    <div class="form-text">Laissez vide si vous ne souhaitez pas le modifier</div>

                </div>

                <div class="mb-3">
                    <label for="confirmNewPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword">

                    <div class="form-text">Laissez vide si vous ne souhaitez pas le modifier</div>

                </div>

                <div class="g-recaptcha mb-4" data-sitekey="<?= $_ENV['CAPTCHA_SITE_KEY'] ?>" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired"></div>

                <div id="div-submit"></div>

            </form>

        </main>

        <?php
        include PATH_SCRIPT . 'functionsJs.php';
        include PATH_SCRIPT . 'footer.php';
        ?>

        <script>

            $('form').submit(function (e) {

                if(confirm("Voulez-vous vraiment soumettre le formulaire ?")) {

                    if(!isValidEmail($('#emailInscription').val())) {

                        alert("L'adresse email n'est pas valide");

                        return false;

                    }

                } else {

                    return false

                }

            });

        </script>

    </body>

</html>
