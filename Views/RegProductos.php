<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Productos</title>
    <?php  require '../Util/conexionTienda.php' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/principalStyle.css">
</head>
<body>
    <?php
        session_start();
        $rol = $_SESSION["Rol"];
        $usuario = $_SESSION["usuario"];
        $profile_img = $_SESSION["imagen"];
        if($rol != "Admin"){
            header("Location: index.php");
        }
    ?>
    <?php
        function depurar (string $entrada) : string {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            return $salida;
        }
    
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tempNombre = $_POST["nombre"];
            $tempPrecio = $_POST["precio"];
            $tempDesc = $_POST["descripcion"];
            $tempStock = $_POST["stock"];

            $campObligatorio = "El campo es obligatorio";

            if(strlen($tempNombre) == 0) $err_nombre = $campObligatorio;
            else {
                $regex = "/^[a-zA-Z0-9\s]{1,40}$/";
                if(!preg_match($regex,$tempNombre)) $err_nombre = "El nombre tiene que ser de maximo 40 caracteres (Solo letras numeros y espacios en blanco)";
                else $nombre = $tempNombre;
            }
            if(strlen($tempPrecio) == 0) $err_precio = $campObligatorio;
            else {
                $tempPrecio = floatval($tempPrecio);
                if($tempPrecio <=  0 )$err_precio = "El precio tiene que ser mayor que 0";
                else if ($tempPrecio >= 10000000) $err_precio = "El precio tiene que ser menor que 10000000";
                else $precio = $tempPrecio;
            }
            if(strlen($tempDesc) == 0) $err_desc = $campObligatorio;
            else {
                if(strlen($tempDesc) > 255) $err_desc = "La descripcion solo puede tener como máximo 255 caracteres";
                else $descripcion = $tempDesc;
            }
            if(strlen($tempStock) == 0) $err_stock = $campObligatorio;
            else {
                $tempStock = intval($tempStock);
                if($tempStock < 0) $err_stock = "La cantidad minima de stock tiene que ser 0";
                else if($tempStock > 100000) $err_stock = "La cantidad maxima de stock es 100000";
                else $stock = $tempStock;
            }
            if(!isset($_FILES["imagen"])) $err_imgagen = "Es obligatorio subir una foto";
            else {
                if($_FILES["imagen"]["type"] === "image/png" || $_FILES["imagen"]["type"] === "image/jpg" || $_FILES["imagen"]["type"] === "image/jpeg"){
                    if($_FILES["imagen"]["size"] >= (1e+6)) $err_imgagen = "El tamaño maximo de la imagen tiene que ser de 1 MegaByte";
                    else {
                        $nombreArchivo = $_FILES['imagen']['name'];
                        $rutaTemporal = $_FILES['imagen']['tmp_name'];
                    }
                } else $err_imgagen = "La imagen solo puede ser PNG, JPG o JPEG";
            }
        }
    ?>
    <nav>
        <div class="logo-ppal">
            <a href="./index.php"><img src="./IMG/logo.png" alt="" width="50px"></a>
            <p>TIENDA</p>
        </div>
        <form class="d-flex" role="search" id="s-form">
            <input class="form-control me-2" type="search" disabled value="Search" aria-label="Search" readonly>
            <button class="register-btn" type="submit" id="btn-buscar" disabled><img src="./IMG/lupa.svg" alt=""></button>
        </form>
        <?php if($rol === "Admin") echo '<a id="aProd" href="./RegProductos.php">Subir Productos</a>' ?>
        <p>Estas logeado como: <?php echo $usuario ?></p>
        <div class="btns">
        <a href="./zonaUsuario.php" title="Zona de Usuario"><img src="<?php echo $profile_img ?>" alt="" id="profile-icon"></a>
            <a href="./cesta.php" class="register-btn"><img src="./IMG/cesta.svg" alt="" width="30px"></a>
            <button class="register-btn"><a href="./RegUsuarios.php" id="btn-a">Registrate</a></button>
            <button class="register-btn"><a href="./login.php" id="btn-a">Iniciar Sesion</a></button>
            <button class="register-btn"><a href="../Util/cerrarSesion.php" id="btn-a">Cerrar Sesion</a></button>
        </div>
    </nav>
    <div class="container">
        <h1>Registro de productos</h1>
        <form action="" method="post" onsubmit="return validarFormulario()" enctype="multipart/form-data">
            <div class="form-floating mb-3">
                <input type="text" class="form-control reg" id="floatingInput" name="nombre" placeholder="Nombre:">
                <label for="floatingInput">Nombre del producto: </label>
                <?php  if(isset($err_nombre)) echo '<p class="error">' . $err_nombre.'</p>'; ?>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control reg" id="floatingPrecio" placeholder="precio: " name="precio" step="0.01">
                <label for="floatingPrecio">Precio: </label>
                <?php  if(isset($err_precio)) echo '<p class="error">' . $err_precio.'</p>'; ?>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control reg" placeholder="Leave a comment here" id="floatingTextarea" name="descripcion"></textarea>
                <label for="floatingTextarea" class="form-label" class="form-control">Descripcion del producto:</label>
                <?php  if(isset($err_desc)) echo '<p class="error">' . $err_desc .'</p>'; ?>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control reg" id="stock" placeholder="stock: " name="stock">
                <label for="stock">Cantidad en stock: </label>
                <?php  if(isset($err_nombre)) echo '<p class="error">' . $err_nombre.'</p>'; ?>
            </div>
            <div class="form-floating mb-3">
                <input type="file" class="reg" name="imagen" id="imagen">
                <?php  if(isset($err_imgagen)) echo '<p class="error">' . $err_imgagen.'</p>'; ?>
            </div>
            <input type="submit" value="Registrar" class="btn btn-primary">
        </form>
    </div>
    <?php
        if(isset($nombre) && isset($precio) && isset($descripcion) && isset($stock) && isset($nombreArchivo) && isset($rutaTemporal)){   
            
            $rutaDestino = './IMG/productPictures/' . $nombreArchivo;
            move_uploaded_file($rutaTemporal, $rutaDestino);
            
            try {
                $sql = "INSERT INTO productos(nombreProducto,precioProducto,descProducto,cantidad,imagen) values ('$nombre','$precio','$descripcion','$stock','$rutaDestino')";
                $conexion->query($sql);
            }catch (mysqli_sql_exception $e) {
                echo  $e->getMessage();
            }
        }
    ?>
    <script src="./JS/validar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>