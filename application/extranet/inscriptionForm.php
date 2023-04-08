<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cookorama - Inscription";
include '../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';

?>

<body>

<main>

    <h2 class="text-center mt-4">Inscription</h2>

    <form action="<?= ADDRESS_FORM ?>inscription.php" method="post" id="inscriptionForm" class="col-md-6" enctype="multipart/form-data" style="margin: 0 auto; padding: 15px;">

        <div class="mb-3">
            <label for="name" class="form-label">Nom *</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="surname" class="form-label">Prénom *</label>
            <input type="text" class="form-control" id="firstname" name="firstname" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" id="emailInscription" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe *</label>
            <input type="password" class="form-control" id="passwordInscription" name="password" required>

            <div id="viewPassword" class="form-text">

                <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordInscription')">
                <label class="form-label">Voir le mot de passe</label>

            </div>

        </div>

        <div class="mb-3">
            <label for="passwordConf" class="form-label">Confirmation du mot de passe *</label>
            <input type="password" class="form-control" id="passwordInscriptionConf" name="passwordConf" required>

            <div id="viewPassword" class="form-text">

                <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordInscriptionConf')">
                <label class="form-label">Voir le mot de passe</label>

            </div>
        </div>

        <div class="mb-3">
            <label for="birthday" class="form-label">Date de naissance *</label>
            <input type="date" class="form-control" id="birthday" name="birthday" required>
        </div>

        <div class="mb-3">
            <label for="profilePicture" class="form-label">Photo de profil</label>
            <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/jpeg, image/png, image/jpg">
        </div>

        <div class="g-recaptcha mb-4" data-sitekey="<?= $_ENV['CAPTCHA_SITE_KEY'] ?>" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired"></div>

        <input type="submit" class="btn" value="Submit" onclick="verifyRecaptcha()" style="display:none;" id="submitButton">

    </form>

</main>

<?php
include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';
?>

<script>


    $('#inscriptionForm').submit(function (e) {

        if(confirm("Voulez-vous vraiment soumettre le formulaire ?")) {

            if ($('#passwordInscription').val() !== $('#passwordInscriptionConf').val()) {

                alert("Les mots de passe ne correspondent pas");

                return false;

            }

            if(!isValidEmail($('#emailInscription').val())) {

                alert("L'adresse email n'est pas valide");

                return false;

            }

        } else {
            return false;
        }

    });

</script>

</body>

</html>