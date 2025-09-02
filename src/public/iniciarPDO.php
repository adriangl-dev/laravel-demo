<?php
//Credenciales de la base de datos
$host = "database"; //database
$dbName = "demo";
$usuario = "root";
$password = "Pass1234"; //

//Conexion con la base de datos
$pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $usuario, $password);

//Recuerda al acabar cerrar la conexion con $pdo = null;
?>