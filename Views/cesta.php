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
        } else {
            /* header("Location: RegUsuarios.php"); */
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $profile_img = "./IMG/profileIcon.png";
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
        <p>Bienvenido, <?php echo $usuario ?></p>
        <div class="btns">
            <a href="./zonaUsuario.php"><img src="<?php echo $profile_img ?>" alt="" id="profile-icon"></a>
            <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
            <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
            <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
            <button class="register-btn"><a href="../Util/cerrarSesion.php" id="btn-a">Cerrar Sesion</a></button>
        </div>
    </nav>
    <div class="container">
        <?php
            $sql = "SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad, pc.idProducto FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto;";
            $resultado = $conexion->query($sql);

            if($resultado -> num_rows === 0){
                $err = "No hay prodcutos en la cesta";
            } else {
                echo '<table class="table">'; 
                echo '<tr><th></th><th>Proudcto</th><th>Precio</th><th>Cantidad</th><th>Precio total</th></tr>';
                $totalCesta = 0;
                while($fila = $resultado -> fetch_assoc()) {
                    echo '<tr><td>'?> <img src="<?php echo $fila["imagen"]?>" alt=""> <?php echo '</td>';
                    echo '<td>' . $fila["nombreProducto"]. '</td>';
                    echo '<td>' . $fila["precioProducto"]. '</td>';
                    echo '<td>' . $fila["cantidad"]. '</td>';
                    
                    $sql = "SELECT pc.cantidad * p.precioProducto AS total FROM productosCestas pc JOIN productos p ON pc.idProducto = p.idProducto where pc.idProducto = " . $fila['idProducto'];
                    $resultado2 = $conexion->query($sql);
                    $total =  $resultado2->fetch_assoc();
                    $totalCesta += doubleval($total['total']);
                    echo "<td>" . $total['total'] . "</td>";
                }       
                echo "</tr></table>";
            }

        ?>
    </div>
    <h2>Precio total de la cesta: <?php echo $totalCesta ?></h2>
    <form action="" method="POST">
        <input type="submit" class="register-btn"value="Finalizar pedido">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>