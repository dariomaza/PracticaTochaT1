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
            }

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
            $rol = "invitado";
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
        <?php if( $rol === "Admin") echo '<a href="./RegProductos.php">Subir Productos</a>' ?>
        <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
        <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
        <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
        <button class="register-btn"><a href="../Util/cerrarSesion.php" id="btn-a">Cerrar Sesion</a></button>
    </nav>
    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST["idProducto"])){
                $idProducto = $_POST["idProducto"];
                echo $idProducto;
                $sql = "SELECT idCesta FROM cestas where usuario = '$usuario'";

                $resultado = $conexion->query($sql);
                
                if($resultado -> num_rows === 0){
                    $err = "";
                } else {
                    while($fila = $resultado -> fetch_assoc()) {
                        $idCesta = $fila["idCesta"];
                    }       
                }
                $sql = "INSERT INTO productosCestas (idProducto, idCesta, cantidad) VALUES ('$idProducto','$idCesta',1)";
                $conexion->query($sql);

            }
        }
    ?>
    <div class="ppal-container">
            <?php
            $sql = "SELECT * FROM productos";
            $resultado = $conexion->query($sql);

            while ($row = $resultado -> fetch_assoc()){               
                echo '<div class="prod-container">';
                ?>  <img src="<?php echo $row["imagen"]; ?>" alt="" width="250px" id="prod-img"><?php 
                echo "<h2>" . $row["nombreProducto"] . "</h2>";
                echo "<p id='prod-desc'>" . $row["descProducto"] . "</p>";
                echo "<p id='prod-precio'>" . $row["precioProducto"] . " €</p>"; ?>
                <form action="" method="post" class="cesta">
                    <input type="hidden" name="idProducto" value="<?php echo $row["idProducto"]?>">
                    <button type="submit" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></button><!--  //? Manda por el formulario el ID de cada uno de los productos -->
                </form>
                <?php
                echo "</div>";
            }
            ?>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>