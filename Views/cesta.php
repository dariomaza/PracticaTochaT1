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
            $rol = $_SESSION["Rol"];
            $sql = "SELECT * FROM fotosUsuarios where usuario = '$usuario';";
            $resultado = $conexion->query($sql);

            if($resultado -> num_rows === 0){
                $profile_img = "./IMG/profileIcon.png";
            } else {
                while($fila = $resultado -> fetch_assoc()) {
                    $profile_img = $fila["ruta"];
                }       
            }

           if($usuario === "invitado"){
                $warnCesta = "Inicia sesion para ver tu cesta";  
           } else {
                $sql = "SELECT idCesta FROM cestas where usuario = '$usuario';";
                $resultadoCesta = $conexion->query($sql);

                $CestaTMP = $resultadoCesta->fetch_assoc();
                $idCesta = $CestaTMP['idCesta'];
           }

        } else {
            /* header("Location: RegUsuarios.php"); */
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./IMG/profileIcon.png";
        }
    ?>
    <nav>
        <div class="logo-ppal">
            <a href="./index.php"><img src="./IMG/logo.png" alt="" width="50px"></a>
            <p>TIENDA</p>
        </div>
        <form class="d-flex" role="search" id="s-form">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="register-btn" type="submit" id="btn-buscar"><img src="./IMG/lupa.svg" alt=""></button>
        </form>
        <?php if($rol === "Admin") echo '<a id="aProd" href="./RegProductos.php">Subir Productos</a>' ?>
        <p>Bienvenido, <?php echo $usuario ?></p>
        <div class="btns">
        <a href="./zonaUsuario.php" title="Zona de Usuario"><img src="<?php echo $profile_img ?>" alt="" id="profile-icon"></a>
            <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
            <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
            <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
            <button class="register-btn"><a href="../Util/cerrarSesion.php" id="btn-a">Cerrar Sesion</a></button>
        </div>
    </nav>
    <div class="container">
        <?php
           if(isset($idCesta)){
                $sql = "SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad, pc.idProducto FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto where pc.idCesta = $idCesta";
                $resultado = $conexion->query($sql);

                if($resultado -> num_rows === 0){
                    $err = "No hay productos en la cesta";
                } else {
                    echo '<table class="table">'; 
                    echo '<tr><th></th><th>Proudcto</th><th>Precio</th><th>Cantidad</th><th>Precio total</th></tr>';
                    $totalCesta = 0;
                    while($fila = $resultado -> fetch_assoc()) {
                        echo '<tr><td>'?> <img src="<?php echo $fila["imagen"]?>" alt="" width="300px"> <?php echo '</td>';
                        echo '<td>' . $fila["nombreProducto"]. '</td>';
                        echo '<td>' . $fila["precioProducto"]. '</td>';
                        echo '<td>' . $fila["cantidad"]. '</td>';
                        $idProd = $fila["idProducto"];
                        
                        $sql = "SELECT pc.cantidad * p.precioProducto AS total FROM productosCestas pc JOIN productos p ON pc.idProducto = p.idProducto where pc.idProducto = $idProd and pc.idCesta = $idCesta;";
                        $resultado2 = $conexion->query($sql);
                        $total =  $resultado2->fetch_assoc();
                        $totalCesta += doubleval($total['total']);
                        echo "<td>" . $total['total'] . "</td>";
                    }       
                    echo "</tr></table>";
                }
           }

        ?>
    </div>
    <h2><?php if(isset($warnCesta)) echo $warnCesta ?></h2>
    <h2><?php if(isset($totalCesta)) {
            echo "El precio total de la cesta es: $totalCesta €";
            $sql = "UPDATE cestas SET precio_total = $totalCesta WHERE idCesta = $idCesta;";
            $conexion->query($sql);
        } else echo "Tu cesta esta vacia " ?></h2>
    <form action="" method="POST">
        <input type="submit" class="register-btn" id="pedido-btn" value="Finalizar pedido" <?php if($usuario == "invitado") echo "disabled" ?>>
        <input type="hidden" name="f" value="f">
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST["f"])){
                $sql = "INSERT INTO pedidos (usuario, precioTotal) VALUES ('$usuario','$totalCesta')";
                $conexion->query($sql);


                $sql = "SELECT idPedido FROM pedidos where usuario = '$usuario'";
                $resultado = $conexion->query($sql);
                $idPedidoTemp = $resultado->fetch_assoc();
                $idPedido = $idPedidoTemp["idPedido"];
                
                $sql = "SELECT p.idProducto, p.precioProducto, pc.cantidad FROM productos AS p JOIN productosCestas AS pc ON p.idProducto = pc.idProducto WHERE pc.idCesta = $idCesta;";
                $resultado = $conexion->query($sql);
                
                
                
                $cont = 1;
                while ($fila = $resultado->fetch_assoc()) {
                    $idProducto = $fila["idProducto"];
                    $precioUni = $fila["precioProducto"];
                    $cantidad = $fila["cantidad"];

                    $alterProds = "UPDATE productos SET cantidad = (cantidad - $cantidad) WHERE idProducto = $idProducto;";
                    $conexion->query($alterProds); /* //! Cuando el usuario realiza el pedido se resta la cantidad comprada a stock total del producto */

                    $sql = "INSERT INTO lineaspedidos (lineaPedido, idProducto, precioUnitario, cantidad, idPedido) VALUES ('$cont','$idProducto','$precioUni','$cantidad','$idPedido')";
                    $conexion->query($sql);

                    $cont++;
                }

                $sql = "DELETE FROM productosCestas WHERE idCesta = $idCesta;";
                $conexion->query($sql);
                $sql = "UPDATE cestas SET precio_total = 0 WHERE idCesta = $idCesta;";
                $conexion->query($sql);
            }
        }
        
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>