<?php 
    //variables para acceder al servidor de base de datos 
    $servidor = "localhost";
    $usuario = "root";
    $password = "";//usuario root no tiene contraseña
    $baseDatos = "fs2025_peliculas";

    //conexión con mysqli
    $conexion = mysqli_connect($servidor, $usuario, $password,$baseDatos);
    if(!$conexion){
        die("Error en conexion ". mysqli_connect_error());
    }
?>