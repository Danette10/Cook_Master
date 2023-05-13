<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

<script>

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    $(document).ready(function() {

        $(window).scroll(function() {
            if ($(this).scrollTop() > 1) {
                $('header').css('position', 'sticky');
            } else {
                $('header').css('position', 'relative');
            }

        });
    });

    <?php
        /*
         * TODO: Function to connect user
         */
    ?>

    function connexion(email, password) {

        let error = document.getElementById("error");

        if (email === "" || password === "") {

            alert("Veuillez remplir tous les champs");

        } else {

            if (isValidEmail(email)) {

                $.ajax({

                    url: "<?= ADDRESS_FORM ?>connexion.php",
                    type: "POST",
                    data: {
                        email: email,
                        password: password
                    },

                    success: function (data) {

                        if (data === "success") {

                            window.location.href = "<?= ADDRESS_SITE ?>";

                        } else {

                            $("#password").val("");
                            error.innerHTML = data;

                        }

                    }

                });

            } else {

                alert("Veuillez entrer une adresse email valide");

            }

        }

    }

    <?php
    /*
     * TODO: Function to display password
     */
    ?>

    function displayPassword(id = "password") {
        let password = document.getElementById(id);
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }

    <?php

        /*
         * TODO: Function to check if email is valid
         */

    ?>

    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    <?php

        /*
         * TODO: Function to check if name is valid
         */

    ?>
    function nameRules() {
        var element = document.getElementById("nameRules");
        var i = 0;
        var rule1 = document.getElementById("nameRule");
        
        var nameValue = document.getElementById("name").value;

        element.removeAttribute("hidden");
        if(nameValue.length >= 2 && nameValue.length <= 40) {
            rule1.setAttribute("style","color: green;");
            i++;
        }else {
            rule1.setAttribute("style","color: red;");
        }
        return i ;
    }
    <?php

        /*
         * TODO: Function to check if firstname is valid
         */

    ?>
    function firstnameRules() {
        var element = document.getElementById("firstnameRules");
        var i = 0;
        var rule1 = document.getElementById("firstnameRule");

        var firstnameValue = document.getElementById("firstname").value;

        element.removeAttribute("hidden");
        if(firstnameValue.length >= 2 && firstnameValue.length <= 40) {
            rule1.setAttribute("style","color: green;");
            i++;
        }else {
            rule1.setAttribute("style","color: red;");
        }
        return i;
    }
    <?php

        /*
         * TODO: Function to check if pwd is valid
         */

    ?>
    function pwdRules() {
        var element = document.getElementById("pwdRules");

        var rule1 = document.getElementById("pwdRule1");
        var rule2 = document.getElementById("pwdRule2");
        var rule3 = document.getElementById("pwdRule3");
        var rule4 = document.getElementById("pwdRule4");
        var rule5 = document.getElementById("pwdRule5");
        var pwdValue = document.getElementById("passwordInscription").value;

        var i = 0;

        var rule2Regex = /(?=.*[a-z](?=.*[a-z]))/;
        var rule3Regex = /(?=.*[A-Z](?=.*[A-Z]))/;
        var rule4Regex = /(?=.*\d(?=.*\d))/;
        var rule5Regex = /(?=.*[^a-zA-Z0-9](?=.*[^a-zA-Z0-9]))/;
        element.removeAttribute("hidden");
        if(pwdValue.length < 8) {
            rule1.setAttribute("style","color: red;");
        }else {
            rule1.setAttribute("style","color: green;");
            i++;
        }  
        if(rule2Regex.test(pwdValue)) {
            rule2.setAttribute("style","color: green;");
            i++;
        }else {
            rule2.setAttribute("style","color: red;");
        }
        if(rule3Regex.test(pwdValue)) {
            rule3.setAttribute("style","color: green;");
            i++;
        }else {
            rule3.setAttribute("style","color: red;");
        }
        if(rule4Regex.test(pwdValue)) {
            rule4.setAttribute("style","color: green;");
            i++;
        }else {
            rule4.setAttribute("style","color: red;");
        }
        if(rule5Regex.test(pwdValue)) {
            rule5.setAttribute("style","color: green;");
            i++;
        }else {
            rule5.setAttribute("style","color: red;");
        }
        if (i != 5 ) {
            return 0;
        }else {
            return 1;
        }
    }
    <?php

        /*
         * TODO: Function to check if pwd confirmation is valid
         */

    ?>
    function pwdConfirmRules() {
        var element = document.getElementById("pwdConfirmRules");

        var rule1 = document.getElementById("pwdConfirmRule1");
        var pwdValue = document.getElementById("passwordInscription").value;
        var pwdConfValue = document.getElementById("passwordInscriptionConf").value;
        var i = 0;
        
        element.removeAttribute("hidden");
        if (pwdConfValue == pwdValue) {
            rule1.setAttribute("style","color: green;");
            i++;
        }else {
            rule1.setAttribute("style","color: red;");
        }
        return i;
    }
    <?php

        /*
         * TODO: Function to check if captcha is valid
         */

    ?>

    async function verifyRecaptcha(recaptchaResponse) {

        const axios = require('axios');

        const secretKey = '<?= $_ENV['CAPTCHA_SITE_SECRET_KEY'] ?>';
        const apiUrl = 'https://www.google.com/recaptcha/api/siteverify';
        const response = await axios.post(apiUrl, null, {
            params: {
                secret: secretKey,
                response: recaptchaResponse,
            },
        });
        return response.data.success;
    }

    function recaptchaCallback() {

        let submitButton = document.createElement('input');
        submitButton.type = 'submit';
        submitButton.className = 'btn';
        submitButton.value = 'Submit';
        submitButton.onclick = 'verifyRecaptcha()';
        submitButton.id = 'submitButton';

        $('#div-submit').replaceWith(submitButton);

    }

    function recaptchaExpired() {

        $('#submitButton').remove();
        if($('#div-submit').length === 0){
            $('#inscriptionForm').append('<div id="div-submit"></div>');
        }
    }

    /**
     * TODO: Function to delete profil picture
     * @param id
     *
     * @return void
     */

    function deleteProfilPicture(id) {

        $.ajax({
            url: '<?= ADDRESS_SCRIPT ?>ajaxProfil.php',
            type: 'POST',
            data: {
                id: id,
                type: 'deleteProfilPicture'
            },
            success: function (data) {
                if (data !== 'error') {
                    $('#profilPicture').html(data);
                }
            }
        });
    }

    function generateStepsFields() {
        const steps = document.getElementById("stepsOfRecipe");
        const nbOfSteps = recipeSteps.value;
        
        if (steps.hasChildNodes()) {
            while (steps.firstChild) {
                steps.removeChild(steps.firstChild);
            }
        }

        for(var i = 0; i < nbOfSteps; i++) {
            const stepDescription = document.createElement("textarea");
            stepDescription.name = "step" + (i+1);
            stepDescription.id = "step" + (i+1);
            stepDescription.placeholder = "Etape " + (i+1) ;
            stepDescription.required = true;
            stepDescription.classList.add("form-control");
            stepDescription.classList.add("mb-3");
            stepDescription.classList.add("col-3");

            document.getElementById("stepsOfRecipe").appendChild(stepDescription);

        }
    }


    function generateIngredientsFields() {
        const ingredientValue = document.getElementById("recipeIngredients");
        const ingredientMainField = document.getElementById("recipeIngredientsList");
        const nbOfIngredients = ingredientValue.value;
            
            if (ingredientMainField.hasChildNodes()) {
                while (ingredientMainField.firstChild) {
                    ingredientMainField.removeChild(ingredientMainField.firstChild);
                }
            }

            for(var i = 0; i < nbOfIngredients; i++) {
                const alignementRow1 = document.createElement("div");
                alignementRow1.classList.add("col-1");
                const alignementRow2 = document.createElement("div");
                alignementRow2.classList.add("col-10");
                alignementRow2.classList.add("mb-3");
                alignementRow2.classList.add("row");
                const alignementRow3 = document.createElement("div");
                alignementRow3.classList.add("col-1");
                const ingredientRow = document.createElement("div");
                ingredientRow.classList.add("row");
                ingredientRow.classList.add("mb-3");
                ingredientRow.classList.add("align-items-center");
                
                const ingredientName = document.createElement("input");
                ingredientName.type = "text";
                ingredientName.name = "ingredientName" + (i+1);
                ingredientName.id = "ingredientName" + (i+1);
                ingredientName.placeholder = "Nom de l'ingrédient " + (i+1) ;
                ingredientName.required = true;
                ingredientName.classList.add("form-control");
                ingredientName.classList.add("col");

                const ingredientQuantity = document.createElement("input");
                ingredientQuantity.type = "number";
                ingredientQuantity.name = "ingredientQuantity" + (i+1);
                ingredientQuantity.id = "ingredientQuantity" + (i+1);
                ingredientQuantity.placeholder = "Quantité de l'ingrédient " + (i+1) ;
                ingredientQuantity.required = true;
                ingredientQuantity.classList.add("form-control");
                ingredientQuantity.classList.add("col");

                const ingredientUnit = document.createElement("select");
                ingredientUnit.name = "ingredientUnit" + (i+1);
                ingredientUnit.id = "ingredientUnit" + (i+1);
                ingredientUnit.required = true;
                ingredientUnit.classList.add("form-control");
                ingredientUnit.classList.add("col");
                ingredientUnit.options.add( new Option("g", "g"));
                ingredientUnit.options.add( new Option("kg", "kg"));
                ingredientUnit.options.add( new Option("ml", "ml"));
                ingredientUnit.options.add( new Option("cl", "cl"));
                ingredientUnit.options.add( new Option("l", "l"));
                ingredientUnit.options.add( new Option("cuillère à café", "cuillère à café"));
                ingredientUnit.options.add( new Option("cuillère à soupe", "cuillère à soupe"));
                ingredientUnit.options.add( new Option("verre", "verre"));
                ingredientUnit.options.add( new Option("pincée", "pincée"));
                

                ingredientMainField.appendChild(ingredientRow);         
                ingredientRow.appendChild(alignementRow1);
                ingredientRow.appendChild(alignementRow2);
                ingredientRow.appendChild(alignementRow3);
                alignementRow2.appendChild(ingredientName);
                alignementRow2.appendChild(ingredientQuantity);
                alignementRow2.appendChild(ingredientUnit);
            }
}
</script>