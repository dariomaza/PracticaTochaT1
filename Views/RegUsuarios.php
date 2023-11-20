<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <?php  require '../Util/conexionTienda.php' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        function depurar (string $entrada) : string {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            return $salida;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tUsuario = depurar($_POST["usuario"]);
            $tNombre = $_POST["nombre"];
            $tApellido = $_POST["apellido"];
            $tFecha = $_POST["fecha_nacimiento"];
            $tContra = $_POST["contrasena"];

            if(strlen($tUsuario) == 0) $err_usuario = "El campo es obligatorio";
            else {
                $regex = "/^[a-zA-z_][a-zA-Z0-9_]{3,11}$/";
                if(!preg_match($regex, $tUsuario)) $err_usuario = "El nombre de usuario debe contener de 4 a 12 caracteres";
                else $usuario = $tUsuario;
            }

            if(strlen($tNombre) == 0) $err_nombre = "El campo es obligatorio";
            else {
                $regex = "/^[a-zA-z][a-zA-Z ]{1,19}$/";
                if(!preg_match($regex, $tNombre)) $err_nombre = "El nombre debe contener de 2 a 20 caracteres";
                else $nombre = $tNombre;
            }

            if(strlen($tApellido) == 0) $err_apellido = "El campo es obligatorio";
            else {
                $regex = "/^[a-zA-z][a-zA-Z ]{1,39}$/";
                if(!preg_match($regex, $tApellido)) $err_apellido = "El apellido debe contener de 2 a 40 caracteres";
                else $apellido = $tApellido;
            }

            if(strlen($tContra) == 0) $err_contra = "La contraseña es obligatoria";
            else {
                $regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!])(?!.*\s).{8,}$/";
                if(!preg_match($regex,$tContra)) $err_contra = "La contraseña no cumple con los requisitos minimos (Minimo 8 caracteres, 1 mayuscula, 1 caracter especial y 1 numero).";
                else $contrasena_cifrada = password_hash($tContra, PASSWORD_DEFAULT);
            }

            $fActual = date("Y-m-d");
            $fDiff = date_diff(date_create($tFecha), date_create($fActual));
            $edad = $fDiff->y;

            if($edad > 12 && $edad < 120) $chekedAge = true;
            else $chekedAge = false;
        }

    ?>
    
    <div class="container" id="div-ppal">
        <h1 id="titulo">Registrate</h1>
        <form action="" method="post" onsubmit="return validarFormulario()">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario: </label>
                <input type="text" name="usuario"  class="form-control">
                <?php  if(isset($err_usuario)) echo '<p class="error">' . $err_usuario.'</p>'; ?>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre: </label>
                <input type="text" name="nombre"  class="form-control">
                <?php  if(isset($err_nombre)) echo '<p class="error">' . $err_nombre.'</p>'; ?>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido: </label>
                <input type="text" name="apellido"  class="form-control">
                <?php  if(isset($err_apellido)) echo '<p class="error">' . $err_apellido.'</p>'; ?>
            </div>
            <div class="mb-3" id="divPass">
                <label for="contrasena" class="form-label">Contraseña: </label>
                <input type="password" name="contrasena" id="contrasena"  class="form-control">
                <img src="./IMG/ojo.png" alt="" onclick='showPass()'>
            </div>
            <?php if(isset($err_contra)) echo '<p class="error">' . $err_contra.'</p>'; ?>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento: </label>
                <input type="date" name="fecha_nacimiento"  class="form-control">
            </div>
            <div class="mb-3">
                <a href="./login.php" id="ALogin">¿Ya tienes cuenta? <span id="und">Inicia sesion</span></a>
            </div>
            <input type="submit" value="Registrarse" class="register-btn">
        </form>
    </div>
    <div id="disabled-background" style="display:none"></div>
    
    <?php
        if(isset($usuario) && isset($nombre) && isset($apellido) && isset($chekedAge) && isset($contrasena_cifrada)){
            
            try {
                echo '<div class="container container-login" id="div-modal">';
                if($chekedAge) {
                    $sql = "INSERT INTO usuarios(usuario, contrasena ,nombre, apellidos, fechaNacimiento) VALUES ('$usuario','$contrasena_cifrada','$nombre', '$apellido', '$tFecha')"; 
                    $conexion->query($sql);
                    if(isset($usuario)) {
                        
                        echo "<h3>Usuario: $usuario registrado correctamente. Ya puedes <a href='login.php'>iniciar sesion</a></h3>";   
                        ?> <script> /* desabilitar(); */ document.getElementById('disabled-background').style.display = "block"; </script> <?php
                        $sql = "INSERT INTO cestas (usuario) VALUES ('$usuario')";
                        $conexion->query($sql);
                    }
                    
                } else {
                    echo '<h3 class="error">Tienes que tener entre 12 y 120 años para registrarte</h3>';
                    echo '<button onclick="aceptado()" class="register-btn">Aceptar</button>';
                    ?> <script> document.getElementById('disabled-background').style.display = "block"; </script> <?php 
                }
                echo '</div>';
            }catch (mysqli_sql_exception $e) {
                echo  $e->getMessage();
            }
        }
    ?>
    <script src="./JS/validar.js"></script>
    <script>
        function aceptado() {
            const div = document.getElementById("div-modal");
            div.style.zIndex = -1;
            document.getElementById('disabled-background').style.display = "none";
        }
    </script>
    <script src="./JS/showPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>