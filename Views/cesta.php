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
                $profile_img = "./IMG/profileIcon.png";
            } else {
                while($fila = $resultado -> fetch_assoc()) {
                    $profile_img = $fila["ruta"];
                }       
            }
        } else {
            /* header("Location: RegUsuarios.php"); */
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./IMG/profileIcon.png";
        }
    ?>
    <nav>
        <img src="<?php echo $profile_img ?>" alt="" id="profile-icon">
        <p>Bienvenido, <?php echo $usuario ?></p>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="register-btn" type="submit" id="btn-buscar"><img src="./IMG/lupa.svg" alt=""></button>
        </form>
        <a href="./zonaUsuario.php">Tu zona</a>
        <a href="./subirFoto.php">Actualizar foto perfil</a>
        <?php if($_SESSION["usuario"] === "Admin") echo '<a href="./RegProductos.php">Subir Productos</a>' ?>
        <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
        <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
        <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
    </nav>
    <div>
        <?php
            $sql = "select productos.nombreProducto, productos.imagen, productos.precioProducto, productosCestas.cantidad from productosCestas join cestas join productos join usuarios where usuarios.usuario = '$usuario'";
            $resultado = $conexion->query($sql);

            if($resultado -> num_rows === 0){
                $err = "No hay prodcutos en la cesta";
            } else {
                echo '<table class="table table-primary">'; 
                echo '<th><td>Imagen</td><td>Proudcto</td><td>Precio</td><td>Cantidad</td></th>';
                while($fila = $resultado -> fetch_assoc()) {
                    echo '<tr><td>'?> <img src="<?php echo $fila["imagen"]?>" alt=""> <?php echo '</td>';
                    echo '<td>' . $fila["nombreProducto"]. '</td>';
                    echo '<td>' . $fila["precioProducto"]. '</td>';
                    echo '<td>' . $fila["cantidad"]. '</td></tr>';
                }       
                echo "</table>";
            }

        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>