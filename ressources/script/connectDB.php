<?php

$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];

try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $username, $password);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}