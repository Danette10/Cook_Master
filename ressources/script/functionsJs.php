<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

<script>
    const axios = require('axios');

    $(document).ready(function() {

        // Si on scroll sur la page
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
     * TODO: Function to display password
     */
    ?>

    function displayPassword(id) {
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
        $('#submitButton').show();
    }

    function recaptchaExpired() {
        $('#submitButton').hide();
    }

</script>