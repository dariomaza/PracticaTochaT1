<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php  require '../Util/conexionTienda.php' ?>
    <?php  require '../Util/producto.php' ?>
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
                }       
            }
        } else {
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./IMG/profileIcon.png";
            $rol = "cliente";
        }
        $_SESSION["imagen"] = $profile_img;

    ?>
    <nav>
        <div class="logo-ppal">
            <a href="./principal.php"><img src="./IMG/logo.png" alt="" width="50px"></a>
            <p>TIENDA</p>
        </div>
        <form class="d-flex" role="search" id="s-form">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="register-btn" id="btn-buscar"><img src="./IMG/lupa.svg" alt=""></button>
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
    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST["idProducto"])){
                $idProducto = $_POST["idProducto"];
                $tmpCantidad = $_POST["cantidad"];
                $sql = "SELECT idCesta FROM cestas where usuario = '$usuario'";

                $resultado = $conexion->query($sql);
                
                if($resultado -> num_rows === 0){
                    $err = "";
                } else {
                    while($fila = $resultado -> fetch_assoc()) {
                        $idCesta = $fila["idCesta"];
                    }       
                }

                if(isset($idCesta)){
                    $sql = "SELECT EXISTS(SELECT 1 FROM productosCestas WHERE idProducto = '$idProducto' and idCesta = '$idCesta')";
                    $resultado = $conexion->query($sql);
                    $existe = mysqli_fetch_row($resultado)[0];

                    if($existe){
                        $sql = "SELECT cantidad FROM productosCestas WHERE idProducto = '$idProducto'";
                        $resultado = $conexion->query($sql);
                        $cantidad = mysqli_fetch_row($resultado)[0];
                        $nuevaCant =  $cantidad + $tmpCantidad;

                        $sql = "UPDATE productosCestas SET cantidad = '$nuevaCant' WHERE idProducto = '$idProducto' and idCesta = '$idCesta'";
                        $conexion->query($sql);
                    } else {
                        $sql = "INSERT INTO productosCestas (idProducto, idCesta, cantidad) VALUES ('$idProducto','$idCesta',$tmpCantidad)";
                        $conexion->query($sql);
                    }
                }

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
                $producto = new Product($row["idProducto"], $row["nombreProducto"], $row["precioProducto"],$row["descProducto"],$row["cantidad"],$row["imagen"]);
                echo "<h2>" . $producto->nombreProducto . "</h2>";
                echo "<p id='prod-desc'>" . $producto->descripcion . "</p>";
                echo "<p id='prod-precio'>" . $producto->precio . " € <br>Stock: ". $producto->cantidad ."</p>"; ?>
                <form action="" method="post" class="cesta">
                    <input type="hidden" name="idProducto" value="<?php echo $row["idProducto"] ?>">
                    <select class="form-select" name="cantidad" id="cantSel">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
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