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
        <div class="mb-3" id="nameRules" hidden="hidden">
            <div id="nameRule" style="color: red;">Minimum 2 caractères et maximum 40</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nom *</label>
            <input type="text" class="form-control" id="name" name="name" required oninput="nameRules();" >
        </div>
        <div class="mb-3" id="firstnameRules" hidden="hidden">
            <div id="firstnameRule" style="color: red;">Minimum 2 caractères et maximum 40</div>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Prénom *</label>
            <input type="text" class="form-control" id="firstname" name="firstname" required oninput="firstnameRules();">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" id="emailInscription" name="email" required>
        </div>
        <div class="mb-3" id="pwdRules" hidden="hidden">
            <div id="pwdRule1" style="color: red;">Minimum 8 caractères</div>
            <div id="pwdRule2" style="color: red;">Minimum 2 minuscule</div>
            <div id="pwdRule3" style="color: red;">Minimum 2 majuscule</div>
            <div id="pwdRule4" style="color: red;">Minimum 2 chiffres</div>
            <div id="pwdRule5" style="color: red;">Minimum 2 caractères spéciaux</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe *</label>
            <input type="password" class="form-control" id="passwordInscription" name="password" required oninput="pwdRules();">

            <div id="viewPassword" class="form-text">

                <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordInscription')">
                <label class="form-label">Voir le mot de passe</label>

            </div>

        </div>
        <div class="mb-3" id="pwdConfirmRules" hidden="hidden">
            <div id="pwdConfirmRule1" style="color: red;">Doit être égal au mot de passe précédent</div>
        </div>
        <div class="mb-3">
            <label for="passwordConf" class="form-label">Confirmation du mot de passe *</label>
            <input type="password" class="form-control" id="passwordInscriptionConf" name="passwordConf" required oninput="pwdConfirmRules()">

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

        <div id="div-submit"></div>

    </form>

    <?php
    include PATH_SCRIPT . 'functionsJs.php';
    include PATH_SCRIPT . 'footer.php';
    ?>

    <script>


        $('#inscriptionForm').submit(function (e) {

            if(confirm("Voulez-vous vraiment soumettre le formulaire ?")) {
                if (!nameRules()) {
                    alert("Le nom ne réspectent pas les conditions");
                    return false;
                }
                if (!firstnameRules()) {
                    alert("Le prénom ne réspectent pas les conditions");
                    return false;
                }
                if (!pwdRules()) {
                    alert("Le mot de passe ne réspecte pas les normes");
                }
                if (!pwdConfirmRules()) {

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
    
</main>

</body>

</html>