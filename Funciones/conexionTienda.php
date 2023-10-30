<?php
    $servidor = 'localhost';
    $user ='root';
    $pass = 'medac';
    $bbdd = 'tienda';

    $conexion = new Mysqli($servidor,$user,$pass,$bbdd) or die ("Error de conexion");
?>

