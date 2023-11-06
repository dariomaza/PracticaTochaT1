<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require './Funciones/conexionTienda.php' ?>
</head>
<body>
    <h1>Principal</h1>
    <?php 
        session_start(); /* Abre una sesion o recupera una existente */
        if (isset($_SESSION["usuario"])){
            $usuario = $_SESSION["usuario"];
        } else {
            /* header("Location: RegUsuarios.php"); */
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
        }
    ?>
    <h2>Bienvenid@ <?php echo $usuario ?></h2>
    <div class="container">
        <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de nacimiento</th>
                </tr>
                </thead>
                <tbody>
            <?php
            $sql = "SELECT * FROM usuarios";
            $resultado = $conexion->query($sql);

            while ($row = $resultado -> fetch_assoc()){
                echo "<tr>";
                echo "<td>". $row["id"]. "</td>";
                echo "<td>" . $row["usuario"] . "</td>";
                echo "<td>" . $row["contrasena"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["apellidos"] . "</td>";
                echo "<td>" . $row["fechaNacimiento"] . "</td>";
                echo "</tr>";
            }
            ?>    
            </tbody>
        </table>
</body>
</html>