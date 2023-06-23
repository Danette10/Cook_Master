<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

<script>

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    $(document).ready(function() {

        changeLang(localStorage.getItem('language'));

        $(window).scroll(function() {
            if ($(this).scrollTop() > 1) {
                $('header').css('position', 'sticky');
            } else {
                $('header').css('position', 'relative');
            }

        });

        let cartLink = $("#cartLink");

        <?php if (!isset($_SESSION['id'])): ?>

        cartLink.removeAttr("href");

        <?php endif; ?>

    });

    /**
     * TODO: Function to change language
     */

    function changeLang(language) {
        if (language == null) {
            language = 'fr';
        }
        let languageFile;
        if (language === 'fr') {
            languageFile = fetch('<?= ADDRESS_LANG ?>fr.json');
            localStorage.setItem('language', 'fr');
            document.getElementById('languageSelecter').innerHTML = '<img src="<?= ADDRESS_IMG_LANG ?>fr.png" alt="French" class="flagPicture"> FR';
        }
        if (language === 'en') {
            languageFile = fetch('<?= ADDRESS_LANG ?>en.json');
            localStorage.setItem('language', 'en');
            document.getElementById('languageSelecter').innerHTML = '<img src="<?= ADDRESS_IMG_LANG ?>en.png" alt="English" class="flagPicture"> EN';
        }
        languageFile
            .then((response) => response.json())
            .then((data) => {
                Object.keys(data).forEach((key) => {
                    let elements = document.getElementsByClassName(key);
                    if (elements.length > 0) {
                        for (let i = 0; i < elements.length; i++) {
                            let element = elements[i];
                            if (key.includes('lang-placeholder')) {
                                element.placeholder = data[key];
                            } else {
                                element.innerHTML = data[key];
                            }
                        }
                    }
                });
            });
    }

    <?php
        /* Function to autocomplete address */
    ?>

    function autoCompleteAddress(){
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
    }

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

                            window.location.href = "<?= ADDRESS_SITE ?>?type=success&message=Connexion réussie";

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

    /**
     * TODO: Function to add quantity on cart page
     * @param productId
     * @param cartId
     *
     * @return void
     */
    function addProductQuantity(cartId, productId) {

        $.ajax({
            url: '<?= ADDRESS_SCRIPT ?>ajaxCart.php',
            type: 'POST',
            data: {
                productId: productId,
                cartId: cartId,
                type: 'addProductQuantity'
            },
            success: function (data) {
                if (data !== 'error') {
                    $('#productQuantity_' + productId).html(data);
                    $('#nbProducts').html(parseInt($('#nbProducts').html()) + 1);
                    calculateTotalPrice(productId, cartId);
                }
            }
        });
    }

    /**
     * TODO: Function to remove quantity on cart page
     * @param productId
     * @param cartId
     *
     * @return void
     */
    function removeProductQuantity(cartId, productId) {

        $.ajax({
            url: '<?= ADDRESS_SCRIPT ?>ajaxCart.php',
            type: 'POST',
            data: {
                productId: productId,
                cartId: cartId,
                type: 'removeProductQuantity'
            },
            success: function (data) {
                if (data !== 'error') {
                    $('#productQuantity_' + productId).html(data);
                    $('#nbProducts').html(parseInt($('#nbProducts').html()) - 1);
                    calculateTotalPrice(productId, cartId);
                }
            }
        });

    }

    /**
     * TODO: Function to calculate total price on cart page
     * @param productId
     * @param cartId
     *
     * @return void
     */
    function calculateTotalPrice(productId, cartId) {

        $.ajax({
            url: '<?= ADDRESS_SCRIPT ?>ajaxCart.php',
            type: 'POST',
            data: {
                type: 'calculateTotalPrice',
                cartId: cartId,
                productId: productId
            },
            success: function (data) {
                if (data !== 'error') {
                    $('#priceTotal').html(data);
                    $('#priceTotalPerProduct_' + productId).html(parseFloat($('#productPrice_' + productId).html()) * parseInt($('#productQuantity_' + productId).html()));
                }
            }
        });
    }

    /**
     * TODO: Function to format date
     * @param number
     * @returns {string|*}
     */
    function formatWithLeadingZero(number) {
        return number < 10 ? '0' + number : number;
    }

    /**
     * TODO: Function to format date
     * @param date
     * @returns {string}
     */
    function formatDateString(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }
    /**
     * TODO: Function to add button to calendar events
     * @param addButton
     */
    function addButtonToCalendarEvents(addButton) {
        let addEventButton = $('#addEventButton');
        let calendarEvents = $('.calendar-events');

        if(addEventButton.length === 0) {
            calendarEvents.append(addButton);
        } else {
            addEventButton.remove();
            calendarEvents.append(addButton);
        }
    }

    /**
     * TODO: Function to select place
     * @param select
     */
    function selectedPlace(select) {
        if(parseInt(select) === 3){
            $.ajax({
                url: `<?= ADDRESS_SCRIPT_EVENT ?>getPlace.php`,
                type: 'GET',
                success: function(data) {
                    let placeForm = $('#placeForm');
                    placeForm.empty();
                    placeForm.append(data);
                }
            });
        } else {
            $('#placeForm').empty();
        }
    }

    function chooseTypeEvent(select) {

        let nbMaxParticipant = document.getElementById('nbMaxParticipant');
        let endEvent = document.getElementById('endEvent');
        let placeEvent = document.getElementById('placeEvent');

        if(parseInt(select) === 4){

            let nbDayCourse = $('#nbDayCourse');
            let imageTraining = $('#imageTraining');

            nbDayCourse.append('' +
                '<div class="mb-3"><label for="dayCourse" class="form-label">' +
                'Nombre de jours de formation ' +
                '<span style="color: red;">*</span>' +
                '</label><input type="number" class="form-control" id="dayCourse" name="dayCourse" required><' +
                '/div>');

            imageTraining.append('' +
                '<div class="mb-3"><label for="imageTraining" class="form-label">' +
                'Image de la formation ' +
                '<span style="color: red;">*</span>' +
                '</label><input type="file" class="form-control" id="imageTraining" name="imageTraining" required><' +
                '/div>');

            nbMaxParticipant.style.display = 'none';
            $('#typePlace').removeAttribute('required');

            endEvent.style.display = 'none';
            endEvent.removeAttribute('required');
            $('#end').removeAttr('required');

            placeEvent.style.display = 'none';
            placeEvent.removeAttribute('required');
            $('#typePlace').removeAttr('required');

        } else {

            $('#nbDayCourse').empty();
            $('#imageTraining').empty();

            nbMaxParticipant.style.display = 'block';
            $('#nbMaxParticipant').attr('required', 'required');

            endEvent.style.display = 'block';
            $('#end').attr('required', 'required');

            placeEvent.style.display = 'block';
            $('#typePlace').attr('required', 'required');

        }
    }

    function sendMessage() {
        let message = document.getElementById('message').value;
        let idUser = document.getElementById('idReceiver').value;
        let data = {
            action: 'sendMessage',
            message: message,
            idSender: <?= $_SESSION['id'] ?? 0; ?>,
            idReceiver: parseInt(idUser),
            dateSend: new Date(new Date().getTime() + 2 * 3600 * 1000).toISOString().slice(0, 19).replace('T', ' ')
        };

        if(socket.readyState === WebSocket.OPEN) {
            if (message === '') {
                alert('Vous ne pouvez pas envoyer de message vide !');
                return;
            }else{
                socket.send(JSON.stringify(data));
            }
        } else {
            console.error("WebSocket is not open. ReadyState is: ", socket.readyState);
        }

        let chatContentMessages = document.querySelector('.chatContentMessages');
        chatContentMessages.innerHTML += formatSentMessage(data);

        document.getElementById('message').value = '';
    }

    function formatSentMessage(data) {
        return `<div class="messageSender">
                <p>
                    <strong>Vous</strong><br>
                    ${data.message}
                </p>
                <p class="dateSendSender">
                    Le ${new Date(data.dateSend).toLocaleDateString('fr-FR')} à ${new Date(data.dateSend).toLocaleTimeString('fr-FR')}
                </p>
            </div>`;
    }

    function openChat(idUser){
        $('#idUser').val(idUser);
        $('.chat').removeClass('d-none');

        $.ajax({
            url: '<?= ADDRESS_SCRIPT ?>ajaxChat.php',
            type: 'POST',
            data: {
                idReceiver: idUser,
                idSender: <?= $_SESSION['id'] ?? 0; ?>,
                action: 'getMessages'
            },
            success: function (data) {
                $('#idReceiver').val(idUser);
                $('.chatContentMessages').html(data);
            }
        });
    }

    function isTyping() {
        let data = {
            action: 'isTyping',
            idSender: <?= $_SESSION['id'] ?? 0; ?>,
        };

        if(socket.readyState === WebSocket.OPEN) {
            socket.send(JSON.stringify(data));
        } else {
            console.error("WebSocket is not open. ReadyState is: ", socket.readyState);
        }
    }

</script>