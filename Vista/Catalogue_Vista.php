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

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
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
    if(!empty($catalogue))
    { ?>
    
    <div class="filters_container">
        <div class="search_bar">
            <form method="GET" action="">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Barra de búsqueda">
                <button type="submit">
                <i class="fa fa-search"></i>
                </button>
            </form>

            <p>Total de resultados: <?php echo $total_results; ?></p>
        </div>
        <div class="filters">
            <span>Filtros: </span>
            <div class="genre_filter">
                <!-- Formulario de Géneros -->
                <form method="GET" action="">
                    <select name="genre" onchange="this.form.submit()">
                        <option value="">Todos los géneros</option>
                        <?php foreach ($genres as $g): ?>
                            <option value="<?php echo $g['id']; ?>" <?php if ($g['id'] == $genre) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($g['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div class="rate_filter">

            </div>
        </div>
    </div>

    <div class="pagination_links">
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&genre=<?php echo urlencode($genre); ?>&page=<?php echo $page - 1; ?>">Anterior</a>
    <?php endif; ?>

    <?php if (count($catalogue) === $limit): ?>
        <a href="?search=<?php echo urlencode($search); ?>&genre=<?php echo urlencode($genre); ?>&page=<?php echo $page + 1; ?>">Siguiente</a>
    <?php endif; ?>
    </div>

    <div class="movies_container">
        <?php foreach ($catalogue as $movie)
        { ?>
            <div class="movie">
                <img class="movie_picture" src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                <h2 class="movie_title"><?php echo $movie['title'] ?></h2>
                <h3 class="movie_date">(<?php echo $movie['date'] ?>)</h3>
                <p class="movie_score">Puntuación Media: <span class="score"><?php echo number_format($movie['avg_score'], 1); ?></span> (<span class="score"><?php echo $movie['score_count']; ?></span> votos)</p>
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
    <?php
        }else echo 'No hay películas en la base de datos o ha habido algún problema al conectarse'; 
    ?>

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