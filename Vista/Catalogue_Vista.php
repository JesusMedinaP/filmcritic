<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Catálogo</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/catalogue.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">
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
                <img class="movie_picture" src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                <h2 class="movie_title"><?php echo $movie['title'] ?></h2>
                <h3 class="movie_date">(<?php echo $movie['date'] ?>)</h3>
                <p class="movie_description"> 
                    <?php 
                        if($movie['desc'] != "") echo truncateText($movie['desc']);
                        else echo 'No hay descripción para esta película';
                    ?>
                    </p>
                <a href="<?php echo $movie['url_imdb'] ?>" target="_blank" class="movie_link">IMDB</a>
            </div>
        <?php 
        } ?>
    </div>
    <?php } ?> 

</body>
</html>

<?php
function truncateText($text, $maxLength = 150) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    } else {
        return $text;
    }
}
?>