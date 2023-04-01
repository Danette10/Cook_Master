<?php
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

if($email != ''){
    $message .= ' Cliquez <a href="' . ADDRESS_SCRIPT . 'resendValidationMail.php?email=' . $email . '">ici</a> pour renvoyer le mail de validation';
}

if($type != '' && $message != ''){
    if($type == 'success') {
        echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        exit();
    } else if($type == 'error') {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        exit();
    }
}