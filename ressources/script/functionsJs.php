<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

<script>

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

</script>