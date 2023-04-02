<?php
include 'init.php';

session_start();

session_destroy();

header('Location: ' . ADDRESS_SITE . '?type=success&message=Vous avez bien été déconnecté');
exit();