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
            
            if(isset($_SESSION["Rol"])){
                $rol = $_SESSION["Rol"];
            } else {
                $rol = "cliente";
                $_SESSION["Rol"] = $rol;
            }

            if($resultado -> num_rows === 0){
                $profile_img = "./IMG/profileIcon.png";
            } else {
                while($fila = $resultado -> fetch_assoc()) {
                    $profile_img = $fila["ruta"];
                    $_SESSION["imagen"] = $profile_img;
                }       
            }
        } else {
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./IMG/profileIcon.png";
            $rol = "cliente";
        }

    ?>
    <nav>
        <div class="logo-ppal">
            <a href="./principal.php"><img src="./IMG/logo.png" alt="" width="50px"></a>
            <p>TIENDA</p>
        </div>
        <form class="d-flex" role="search" id="s-form">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="register-btn" type="submit" id="btn-buscar"><img src="./IMG/lupa.svg" alt=""></button>
        </form>
        <?php if($rol === "Admin") echo '<a id="aProd" href="./RegProductos.php">Subir Productos</a>' ?>
        <p>Estas logeado como: <?php echo $usuario ?></p>
        <div class="btns">
            <a href="./zonaUsuario.php"><img src="<?php echo $profile_img ?>" alt="" id="profile-icon"></a>
            <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
            <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
            <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
            <button class="register-btn"><a href="../Util/cerrarSesion.php" id="btn-a">Cerrar Sesion</a></button>
        </div>
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
            <a href="./subirFoto.php">Actualizar foto perfil</a>
    </section>
</body>
</html>
