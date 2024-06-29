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
    <?php if ($_SESSION["user_id"]) { ?>
      <h2>He iniciado sesión</h2>
      <a href="index.php?controlador=catalogue&action=desconectar">Desconectar</a>
    <?php }else{ ?>
        <a href="index.php?controlador=login">Ir al Login</a>
    <?php } ?>

    <?php
    console_log($movies);
    if(!empty($movies))
    { ?>
    
    <div class="movies_container">
        <?php foreach ($movies as $movie)
        { ?>
            <div class="movie">
                <img src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>"     />
                <h3 class="movie_title_date"> <?php echo $movie['title'] ?> - <?php echo $movie['date'] ?></h3>
                <p class="movie_description"> <?php echo $movie['desc'] ?></p>
                <a href="<?php echo $movie['url_imdb'] ?>" target="_blank" class="movie_link">IMDB</a>
            </div>
        <?php 
        } ?>
    </div>
    <?php } ?> 

    
</body>
</html>