<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Principal</h1>
    <?php 
        session_start(); /* Abre una sesion o recupera una existente */
        $usuario = $_SESSION["usuario"];
    ?>
    <h2>Bienvenid@ <?php echo $usuario ?></h2>
</body>
</html>