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