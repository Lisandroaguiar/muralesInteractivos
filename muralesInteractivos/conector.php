<?php
//----------------------------------------------------------------------VARIABLES DEL ENTORNO
ini_set('variables_order', 'EGPCS');

require 'libraries/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$test = $_ENV['TEST'];
echo "<script> console.log('Leyendo .env: $test') </script>";

//----------------------------------------------------------------------CONECTAR CON DATABASE
$server = $_ENV['DB_SERVER'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$db = $_ENV['DB_NAME'];
$conector = new mysqli($server, $user, $pass, $db);

if ($conector->connect_errno) {
    die("F" . $conector->connect_error);
}
