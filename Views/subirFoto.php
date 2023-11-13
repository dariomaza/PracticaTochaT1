<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php  require '../Util/conexionTienda.php' ?>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
    <?php
        session_start();
        $usuario = $_SESSION["usuario"];

        
        if($usuario != "invitado"){
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
        } else {
            $err_updt = "Tienes que iniciar sesion para subir una imagen de perfil";
        }
        
    ?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="imagen" id="imagen">
                <div class="error">
                <?php if(isset($err_updt)) echo $err_updt;?>
                </div>
            </div>
            <input class="register-btn"type="submit" value="Subir imagen">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>