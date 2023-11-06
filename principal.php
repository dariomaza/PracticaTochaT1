<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require './Funciones/conexionTienda.php' ?>
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
        <h2>Bienvenid@ <?php echo $usuario ?></h2>
        <a href="./subirFoto.php" title="actualiza tu foto de perfil"><img src="<?php echo $profile_img ?>" alt="" id="profile-icon"></a>
    </nav>
    <div class="container">
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Descripcion</th>
                        <th>Cantidad en stock</th>                    
                    </tr>
                </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM productos";
                $resultado = $conexion->query($sql);

                while ($row = $resultado -> fetch_assoc()){
                    echo "<tr>";
                    echo "<td>". $row["idProducto"]. "</td>";
                    echo "<td>" . $row["nombreProducto"] . "</td>";
                    echo "<td>" . $row["precioProducto"] . "</td>";
                    echo "<td>" . $row["descProducto"] . "</td>";
                    echo "<td>" . $row["cantidad"] . "</td>";
                    echo "</tr>";
                }
                ?>    
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>