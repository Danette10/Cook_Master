<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cook Master - Inscription";
include '../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';

?>

<body>

<h2 class="text-center mt-4">Inscription</h2>

<form action="<?= PATH_FORM ?>inscription.php" method="post" class="col-md-6" style="margin: 0 auto; padding: 15px;">

    <div class="mb-3">
        <label for="name" class="form-label">Name *</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>

    <div class="mb-3">
        <label for="surname" class="form-label">First name *</label>
        <input type="text" class="form-control" id="firstname" name="firstname">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control" id="emailInscription" name="email">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password *</label>
        <input type="password" class="form-control" id="passwordInscription" name="password">

        <div id="viewPassword" class="form-text">

            <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordInscription')">
            <label class="form-label">Show password</label>

        </div>

    </div>

    <div class="mb-3">
        <label for="passwordConf" class="form-label">Confirm password *</label>
        <input type="password" class="form-control" id="passwordInscriptionConf" name="passwordConf">

        <div id="viewPassword" class="form-text">

            <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordInscriptionConf')">
            <label class="form-label">Show password</label>

        </div>
    </div>

    <div class="mb-3">
        <label for="birthday" class="form-label">Birthday *</label>
        <input type="date" class="form-control" id="birthday" name="birthday">
    </div>

    <input type="submit" class="btn" value="Submit">

</form>

<?php
include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';
?>

<script>

    $('form').submit(function (e) {

        if(confirm("Are you sure you want to submit this form?")) {

            if ($('#passwordInscription').val() !== $('#passwordInscriptionConf').val()) {

                alert("Passwords don't match");

                return false;

            }

            if(!isValidEmail($('#emailInscription').val())) {

                alert("Email is not valid");

                return false;

            }

        } else {
            return false;
        }
    });

</script>

</body>

</html>