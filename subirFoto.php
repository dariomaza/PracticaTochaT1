<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require './Funciones/ConexionTienda.php'?>
</head>
<body>
    <?php
        session_start();
        $usuario = $_SESSION["usuario"];
        
        if (isset($_FILES['imagen'])) {
            
            $nombreArchivo = $_FILES['imagen']['name'];
            $rutaTemporal = $_FILES['imagen']['tmp_name'];

            $rutaDestino = './Recursos/profilePictures/' . $nombreArchivo;
            move_uploaded_file($rutaTemporal, $rutaDestino);
            
            $sql = "INSERT INTO fotosUsuarios (usuario, ruta) values ('$usuario','$rutaDestino')";
            $conexion->query($sql);

            $_SESSION["perfil"] = $rutaDestino;
            header("location: principal.php");
        }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="imagen" id="imagen">
        <input type="submit" value="Subir imagen">
      </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>