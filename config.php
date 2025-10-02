<?php
// config.php
$host = 'localhost';
$dbname = 'vini1368_faculdade_donaduzzi';
$username = 'vini1368_root';
$password = '?O5*lFBe}Fbc';

$questions_to_show = 2;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
