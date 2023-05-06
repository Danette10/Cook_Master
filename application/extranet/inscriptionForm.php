<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cookorama - Inscription";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

?>

<body>

<main>

    <h2 class="text-center mt-4">Inscription</h2>

    <form action="<?= ADDRESS_FORM ?>inscription.php" method="post" id="inscriptionForm" class="col-md-6" enctype="multipart/form-data" style="margin: 0 auto; padding: 15px;">

        <div class="btn-group" role="group" id="divTypeInscription" aria-label="Basic checkbox toggle button group">

            <input type="checkbox" class="btn-check" id="particulier" autocomplete="off" checked>
            <label class="btn" for="particulier" style="z-index: 0;">Particulier</label>

            <input type="checkbox" class="btn-check" id="professionnel" autocomplete="off">
            <label class="btn" for="professionnel">Professionnel</label>

        </div>

        <div class="mb-3">
            <hr class="border border-secondary border-2 opacity-75" style="border-radius: 5px;">
        </div>

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

        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse *</label>
            <input type="text" id="adresse" name="adresse" class="form-control" placeholder="Saisissez une adresse" required>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">Ville *</label>
            <input type="text" id="city" class="form-control" name="city" readonly placeholder="Ville">
        </div>

        <div class="mb-3">
            <label for="postal_code" class="form-label">Code postal *</label>
            <input type="text" id="postal_code" class="form-control" name="postal_code" readonly placeholder="Code postal">
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

        <div id="professionnelForm" style="display:none;">

            <div class="mb-3">
                <label for="diploma" class="form-label">Diplôme *</label>
                <input type="file" class="form-control" id="diploma" name="diploma" accept="image/jpeg, image/png, image/jpg, application/pdf" required>
            </div>

            <div class="mb-3">
                <label for="cardId" class="form-label">Carte d'identité *</label>
                <input type="file" class="form-control" id="cardId" name="cardId" accept="image/jpeg, image/png, image/jpg, application/pdf" required>
            </div>

            <div class="mb-3 d-flex align-items-baseline">
                <input type="checkbox" id="cgu" name="cgu" class="me-2" required>
                <label for="cgu" class="form-label">J'accepte que Cookorama conserve mes données personnelles afin de pouvoir vérifier l'authenticité de mon profil professionnel, et ainsi me permettre de proposer mes services sur la plateforme. En cochant cette case, je certifie que les informations fournies sont exactes et sincères. *</label>
            </div>
        </div>

        <div class="g-recaptcha mb-4" data-sitekey="<?= $_ENV['CAPTCHA_SITE_KEY'] ?>" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired"></div>

        <div id="div-submit"></div>

    </form>

    <script>

        $(document).ready(function() {
            $("#adresse").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "https://api-adresse.data.gouv.fr/search/",
                        dataType: "json",
                        data: {
                            q: request.term,
                            autocomplete: 1
                        },
                        success: function(data) {
                            response($.map(data.features, function(item) {
                                return {
                                    label: item.properties.label,
                                    value: item.properties.label,
                                    city: item.properties.city,
                                    postalCode: item.properties.postcode,
                                    street: item.properties.street || item.properties.name,
                                    number: item.properties.housenumber
                                };
                            }));
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    const formattedAddress = ui.item.number && ui.item.street ? `${ui.item.number} ${ui.item.street}` : ui.item.street || '';
                    $("#adresse").val(formattedAddress);
                    $("#city").val(ui.item.city);
                    $("#postal_code").val(ui.item.postalCode);
                    return false;
                }
            });
        });

        document.getElementById('particulier').addEventListener('change', function() {
            toggleForm('particulier');
        });

        document.getElementById('professionnel').addEventListener('change', function() {
            toggleForm('professionnel');
        });

        function toggleForm(formType) {
            if (formType === 'particulier') {
                if (!document.getElementById('particulier').checked && !document.getElementById('professionnel').checked) {
                    document.getElementById('particulier').checked = true;
                }
                document.getElementById('professionnelForm').style.display = 'none';
                document.getElementById('professionnel').checked = false;
            } else if (formType === 'professionnel') {
                if (!document.getElementById('particulier').checked && !document.getElementById('professionnel').checked) {
                    document.getElementById('professionnel').checked = true;
                }
                document.getElementById('professionnelForm').style.display = 'block';
                document.getElementById('particulier').checked = false;
            }
        }


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

                if($('#adresse').val() === "") {

                    alert("L'adresse est vide");

                    return false;

                }

                if($('#city').val() === "") {

                    alert("La ville est vide");

                    return false;

                }

                if($('#postal_code').val() === "") {

                    alert("Le code postal est vide");

                    return false;

                }

                if($('#professionnel').is(':checked')) {

                    if($('#diploma').val() === "") {

                        alert("Le diplôme est vide");

                        return false;

                    }

                    if($('#cardId').val() === "") {

                        alert("La carte d'identité est vide");

                        return false;

                    }

                    if(!$('#cgu').is(':checked')) {

                        alert("Vous devez accepter les CGU");

                        return false;

                    }

                }

            } else {
                return false;
            }

        });

    </script>
    
</main>

</body>

</html>