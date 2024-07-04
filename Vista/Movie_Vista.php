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
            <h3>Géneros:</h3>
            <?php if($movieGenres != null) { ?>
                <ul>
                    <?php foreach ($movieGenres as $genre) { ?>
                        <li><?php echo htmlspecialchars($genre); ?></li>
                    <?php } ?>
                </ul>
            <?php }else echo '<p>La película no tiene ningún género asociado.</p>' ?>

            <h3>Comentarios:</h3>
            <?php if($movieComments != null) { ?>
                <ul>
                    <?php foreach ($movieComments as $comment) { ?>
                        <li><strong><?php echo htmlspecialchars($comment['name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></li>
                    <?php } ?>
                </ul>
            <?php }else echo '<p>No se han encontado comentarios en la base de datos referentes a esta película.</p>' ?>

            <?php if($_SESSION['user_id']) { ?>
                <h3>Puntuación recibida:</h3>
                <?php if($movieScore != null) { ?>
                    <p><?php echo $movieScore['score']; ?></p>
                <?php }else echo '<p>No has puntuado la película todavía.</p>' ?>

                <h3>Puntuar:</h3>
                <form action="index.php?controlador=movie&action=submit_score" method="POST">
                    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>"/>
                    <input type="number" name="score" min="1" max="5" required>
                    <button type="submit">Enviar puntuación</button>
                </form>

                <h3>Comentar:</h3>
                <form action="index.php?controlador=movie&action=submit_comment" method="POST">
                    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>"/>
                    <textarea name="comment" rows="10" cols="50" placeholder="Escribe tu comentario" required></textarea>
                    <button type="submit">Enviar comentario</button>
                </form>
            <?php } ?>
        </div>

    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>