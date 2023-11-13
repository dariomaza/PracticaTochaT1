<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php  require '../Util/conexionTienda.php' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/principalStyle.css">
</head>
<body>
    <?php 
        session_start(); /* Abre una sesion o recupera una existente */
        if (isset($_SESSION["usuario"])){
            
            $usuario = $_SESSION["usuario"];
            $sql = "SELECT * FROM fotosUsuarios where usuario = '$usuario';";
            $resultado = $conexion->query($sql);

            if($resultado -> num_rows === 0){
                $profile_img = "./Recursos/profileIcon.png";
            } else {
                while($fila = $resultado -> fetch_assoc()) {
                    $profile_img = $fila["ruta"];
                }       
            }
        } else {
            /* header("Location: RegUsuarios.php"); */
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./Recursos/profileIcon.png";
        }
    ?>
    <nav>
        <img src="<?php echo $profile_img ?>" alt="" id="profile-icon">
        <p>Bienvenido, <?php echo $usuario ?></p>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="register-btn" type="submit" id="btn-buscar"><img src="./Recursos/lupa.svg" alt=""></button>
        </form>
        <a href="#">Tu zona</a>
        <a href="./subirFoto.php">Actualizar foto perfil</a>
        <?php if($_SESSION["usuario"] === "Admin") echo '<a href="./RegProductos.php">Subir Productos</a>' ?>
        <a href="#">Cesta <!-- TODO: cambiar por icono --></a>
        <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
        <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
    </nav>
    <section class="datos-usuario">
            <?php
                $sql = "SELECT * FROM usuarios where usuario = '$usuario';";
                $resultado = $conexion->query($sql);

                while ($row = $resultado -> fetch_assoc()){               
                    echo '<div class="container">';
                    echo "<p>Nombre de Usuario: " . $row["usuario"] . "</p>";
                    echo "<p id='prod-desc'>Nombre: " . $row["nombre"] . "</p>";
                    echo "<p id='prod-precio'>Apellido: " . $row["apellidos"] . " </p>";
                    echo "<p id='prod-precio'>Fecha de nacimiento: " . date("j-m-Y",strtotime($row["fechaNacimiento"])). " </p>";
                    echo "</div>";
                }
            ?>   
            <a href="#">Seguimiento de pedidos</a>
            <a href="#">Historial de pedidos</a>
            <a href="#">Datos de pago</a>
            <a href="#">Datos de facturacion</a>
    </section>
</body>
</html>
