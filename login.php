<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>   
    <?php  require './funciones/conexionTienda.php' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <h1 id="titulo">Iniciar Sesion</h1>
        <form action="" method="post" onsubmit="return validarFormulario()" >
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario: </label>
                <input type="text" name="usuario" id="usuario" class="form-control">
    
            </div>
            <div class="mb-3" id="divPass">
                <label for="contrasena" class="form-label">Contraseña: </label>
                <input type="password" name="contrasena" id="contrasena"  class="form-control">
                <img src="./Recursos/ojo.png" alt="" onclick='showPass()'>
            </div>
            <input type="submit" value="Iniciar sesion" class="register-btn">
            <a href="./RegUsuarios.php" class="register-btn">Registrarse</a>
        </form>
    </div>
    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario = $_POST["usuario"];
            $contrasena = $_POST["contrasena"]; //va a venir cifrada de la bbdd en la v2

            $sql = "SELECT * FROM usuarios where usuario = '$usuario'";
            $resultado = $conexion -> query($sql);
            if($resultado -> num_rows === 0){
                echo "El usuario no existe";
            } else {
            while($fila = $resultado -> fetch_assoc()) {
                $contrasena_cifrada = $fila["contrasena"];
            }
            
            $acceso_valido = password_verify($contrasena, $contrasena_cifrada);

            if($acceso_valido) {
                /* VARIABLES PERSISTENTES DURANTE TODA LA SESION EN LA PAGINA WEB */
                session_start();
                $_SESSION["usuario"] = $usuario;
                header("Location: principal.php");
            } else {
                echo "El usuario y/o la contraseña no son validos";
            }
        }
        }
?>


    <script>
        function validarFormulario() {
            var campoUsuario = document.querySelector('input[name="usuario"]').value;
            var campoContrasena = document.querySelector('input[name="contrasena"]').value;

            if (campoUsuario === '' || campoContrasena === '' ) {
                alert("No se admiten campos vacios");
                return false;
            }
            return true;
        }
    </script>
    <script src="./JS/showPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>