<?php
$type = isset($_GET['type']) ?? htmlspecialchars($_GET['type']);
$message = isset($_GET['message']) ?? htmlspecialchars($_GET['message']);

if($type == 'success') {
    echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
} else if($type == 'error') {
    echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
}