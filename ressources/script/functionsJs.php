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

    function addStep() {
        var currenNbOfSteps = parseInt(document.getElementById("nbOfSteps").value);
        
        currenNbOfSteps = parseInt(currenNbOfSteps + 1);
        console.log(currenNbOfSteps);
        document.getElementById("nbOfSteps").value = currenNbOfSteps;
        console.log(currenNbOfSteps);
        const newStepRow = document.getElementById("recipeStepsAddedRow");

        const newStepDiv = document.createElement("div");
        newStepDiv.classList.add("col-12");
        const newStepTextLabel = document.createElement("label");
        newStepTextLabel.setAttribute("for", "recipeStep" + currenNbOfSteps);
        newStepTextLabel.classList.add("form-label");
        newStepTextLabel.innerHTML = "Etape " + currenNbOfSteps;

        const newStepTextarea = document.createElement("textarea");
        newStepTextarea.rows = "3";
        newStepTextarea.placeholder = "Etape " + currenNbOfSteps;
        newStepTextarea.classList.add("form-control");
        newStepTextarea.classList.add("mb-3");
        newStepTextarea.setAttribute("name", "recipeStep" + currenNbOfSteps);
        newStepTextarea.setAttribute("id", "recipeStep" + currenNbOfSteps);

        newStepRow.appendChild(newStepDiv);
        newStepDiv.appendChild(newStepTextLabel);
        newStepDiv.appendChild(newStepTextarea);

            

    }

    function addIngredient() {

        const currentNbrOfIngredients = parseInt(document.getElementById("nbOfIngredrients").value) +1;
        const newIngredientRow = document.getElementById("recipeIngredientsAddedRow");
        const lastIngredient = currentNbrOfIngredients - 1;
        if (lastIngredient > 1) {
            console.log("removeIngredient" + lastIngredient);
            const btnToRemove = document.getElementById("removeIngredient" + lastIngredient);
            btnToRemove.remove();
        }
        document.getElementById("nbOfIngredrients").value = currentNbrOfIngredients;
        console.log(currentNbrOfIngredients);
        
        const removeIngredientBtn = document.createElement("button");
        removeIngredientBtn.classList.add("col-2");
        removeIngredientBtn.classList.add("btn");
        removeIngredientBtn.classList.add("btn-danger");
        removeIngredientBtn.classList.add("mb-3");
        removeIngredientBtn.innerHTML = "Supprimer";
        removeIngredientBtn.setAttribute("onclick", "removeIngredient("+currentNbrOfIngredients+")");
        removeIngredientBtn.setAttribute("id", "removeIngredient" + currentNbrOfIngredients);
        const newIngredientDiv1 = document.createElement("div");
        newIngredientDiv1.classList.add("col-4");
        newIngredientDiv1.setAttribute("id", "ingredientDiv1" + currentNbrOfIngredients);
        const newIngredientDiv2 = document.createElement("div");
        newIngredientDiv2.classList.add("col-3");
        newIngredientDiv2.setAttribute("id", "ingredientDiv2" + currentNbrOfIngredients);
        const newIngredientDiv3 = document.createElement("div");
        newIngredientDiv3.classList.add("col-3");   
        newIngredientDiv3.setAttribute("id", "ingredientDiv3" + currentNbrOfIngredients);

        const newIngredientInput = document.createElement("input");
        newIngredientInput.type = "text";
        newIngredientInput.name = "recipeIngredient" + currentNbrOfIngredients;
        newIngredientInput.id = "recipeIngredient" + currentNbrOfIngredients;
        newIngredientInput.placeholder = "Nom de l'ingrédient";
        newIngredientInput.required = true;
        newIngredientInput.classList.add("form-control");
        newIngredientInput.classList.add("mb-3");

        const newIngredientQuantityInput = document.createElement("input");
        newIngredientQuantityInput.type = "number";
        newIngredientQuantityInput.name = "recipeIngredientQuantity" + currentNbrOfIngredients;
        newIngredientQuantityInput.id = "recipeIngredientQuantity" + currentNbrOfIngredients;
        newIngredientQuantityInput.placeholder = "Quantité";
        newIngredientQuantityInput.required = true;
        newIngredientQuantityInput.classList.add("form-control");

        const newIngredientUnitSelect = document.createElement("select");
        newIngredientUnitSelect.name = "recipeIngredientUnit" + currentNbrOfIngredients;
        newIngredientUnitSelect.id = "recipeIngredientUnit" + currentNbrOfIngredients;
        newIngredientUnitSelect.required = true;
        newIngredientUnitSelect.classList.add("form-control");
        newIngredientUnitSelect.options.add(new Option("g", "g"));
        newIngredientUnitSelect.options.add(new Option("kg", "kg"));
        newIngredientUnitSelect.options.add(new Option("ml", "ml"));
        newIngredientUnitSelect.options.add(new Option("cl", "cl"));
        newIngredientUnitSelect.options.add(new Option("l", "l"));
        newIngredientUnitSelect.options.add(new Option("cuillère à café", "cuillère à café"));
        newIngredientUnitSelect.options.add(new Option("cuillère à soupe", "cuillère à soupe"));
        newIngredientUnitSelect.options.add(new Option("verre", "verre"));
        newIngredientUnitSelect.options.add(new Option("pincée", "pincée"));
        
        
        newIngredientRow.appendChild(newIngredientDiv1);
        newIngredientRow.appendChild(newIngredientDiv2);
        newIngredientRow.appendChild(newIngredientDiv3);
        newIngredientRow.appendChild(removeIngredientBtn);

        newIngredientDiv1.appendChild(newIngredientInput);
        newIngredientDiv2.appendChild(newIngredientQuantityInput);
        newIngredientDiv3.appendChild(newIngredientUnitSelect);


    }

    function removeIngredient(x) {
        document.getElementById("ingredientDiv1" + x).remove();
        document.getElementById("ingredientDiv2" + x).remove();
        document.getElementById("ingredientDiv3" + x).remove();
        document.getElementById("removeIngredient" + x).remove();

        document.getElementById("nbOfIngredrients").value = parseInt(document.getElementById("nbOfIngredrients").value) -1;

        if (x > 2) {
            const newIngredientRow = document.getElementById("recipeIngredientsAddedRow");
            const lastIngredient = x - 1;
            const removeIngredientBtn = document.createElement("button");
            removeIngredientBtn.classList.add("col-2");
            removeIngredientBtn.classList.add("btn");
            removeIngredientBtn.classList.add("btn-danger");
            removeIngredientBtn.classList.add("mb-3");
            removeIngredientBtn.innerHTML = "Supprimer";
            removeIngredientBtn.setAttribute("onclick", "removeIngredient("+lastIngredient+")");
            removeIngredientBtn.setAttribute("id", "removeIngredient" + lastIngredient);
            newIngredientRow.appendChild(removeIngredientBtn);
        }
    }
        
    
</script>