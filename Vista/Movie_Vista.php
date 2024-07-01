<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - <?php echo $movie['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
</head>
<body>
    
    
    <?php if ($movie) { ?>
        <h1>Estoy en el detalle de la película: <?php echo $movie['title'] ?></h1>
        <a href="index.php">Volver al inicio</a>
        <div class="movie_details">
            <h2>Título: <?php echo $movie['title'] ?></h2>
            <p class="movie_description"><?php echo $movie['desc'] ?></p>
        </div>
    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>