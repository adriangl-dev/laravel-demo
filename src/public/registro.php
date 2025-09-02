<?php
// Variables
    $host = 'database';
    $usuario = 'root';
    $password = 'Pass1234';
    $nombreDB = 'demo';

    // Conexión con la base de datos
    $con = mysqli_connect($host, $usuario, $password, $nombreDB) 
        // En caso de error
        or die("No se ha podido establecer conexión con la base de datos.");
?>