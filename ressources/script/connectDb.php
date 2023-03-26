<?php
$username = 'root';
$password = '';
try {
    $db = new PDO('mysql:host=localhost;dbname=cookMaster', $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}