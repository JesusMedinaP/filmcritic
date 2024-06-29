<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Catálogo</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
</head>
<body>   
    <h1>Estoy en el Catálogo</h1>
    <?php if ($_SESSION["user"]) { ?>
      <h2>He iniciado sesión</h2>  
    <?php }else{ ?>
        <a href="index.php?controlador=login">Ir al Login</a>
    <?php } ?>
    
</body>
</html>