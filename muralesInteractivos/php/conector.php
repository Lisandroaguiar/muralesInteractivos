<?php
//----------------------------------------------------------------------CONECTAR CON DATABASE
$server = "localhost";
$user = "root";
$pass = "";
$db = "proyecto_murales";
$conector = new mysqli($server, $user, $pass, $db);

if ($conector->connect_errno) {
    die("F" . $conector->connect_error);
}