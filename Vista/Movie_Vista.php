<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - <?php echo $movie['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/movie.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="navigation_bar">
        <h1 class="title hover_scale"><a href="index.php?controlador=catalogue&action=home">Pelis Review</a></h1>
    </div>
    
    <?php if ($movie) { ?>
        <div class="movie_details_container">
            <div class="movie_picture_info">
                <div class="movie_pic_score">
                    <img class="movie_picture" src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                    <?php if(isset($_SESSION['user_id'])) { ?>
                    <?php if($movieScore != null) { ?>
                        <span class="movie_score"><i class="fa-solid fa-star"></i> <?php echo round($movie['avg_score'], 1); ?> (<?php echo $movie['score_count'] ?> votos)</span>
                    <?php }else echo '<p>No has puntuado la película todavía.</p>' ?>
                    <?php } ?>
                </div>
                <div class="movie_details">
                    <h2>Título: <?php echo $movie['title'] ?></h2>
                    <h3>Fecha de estreno: <?php echo $movie['date'] ?></h3>
                    <div class="movie_genres">
                        <h3>Géneros:</h3>
                        <?php if($movieGenres != null) { ?>
                            <ul>
                                <?php foreach ($movieGenres as $genre) { ?>
                                    <li><?php echo htmlspecialchars($genre); ?></li>
                                <?php } ?>
                            </ul>
                        <?php }else echo '<p>La película no tiene ningún género asociado.</p>' ?>
                    </div>
                    <h3>Fuente: <a href="<?php echo $movie['url_imdb'] ?>" target="_blank">IMDB</a></h3>
                    <?php if(isset($_SESSION['user_id'])) : ?>
                        <?php if($movieScore != null): ?>
                            <p>Tu puntuación: <i class="fa-solid fa-star"></i> <?php echo $movieScore['score'] ?></p>
                            <?php else: echo '<p>No has puntuado la película</p>' ?>
                        <?php endif ?>
                        <?php else: echo '<p>Tienes que iniciar sesión para poder puntuar la película</p>' ?>
                    <?php endif ?>
                </div>
            </div>

            <p class="movie_description">
                Descripción: 
                        <?php 
                            if($movie['desc'] != "" && $movie['desc'] != "N/A") echo $movie['desc'];
                            else echo 'No hay descripción para esta película';
                        ?>
            </p>

            <div>
                <?php if(isset($_SESSION['user_id'])) { ?>
    
                    <h3>Puntuar película:</h3>
                    <form action="index.php?controlador=movie&action=submit_score" method="POST">
                        <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>"/>
                        <input type="number" name="score" min="1" max="5" required>
                        <button class="form_button" type="submit">Puntuar</button>
                    </form>
    
                    <h3>Comentar:</h3>
                    <form action="index.php?controlador=movie&action=submit_comment" method="POST" class="comment_form">
                        <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>"/>
                        <textarea name="comment" rows="5" cols="50" placeholder="Escribe tu comentario" required></textarea>
                        <button class="form_button" type="submit" style="align-self: flex-start;">Comentar</button>
                    </form>
                <?php }else{ ?>
                    <p>Debes iniciar sesión para poder puntuar y comentar la película</p>
                    <?php } ?>
            </div>

            <h3>Comentarios:</h3>
            <?php if($movieComments != null) { ?>
                <div id="comments_container" class="comments_container">
                    <?php foreach ($movieComments as $comment): ?>
                        <div class="comment_card">
                            <p class="comment_author"><?php echo htmlspecialchars($comment['name']); ?></p>
                            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php }else echo '<p>No se han encontado comentarios en la base de datos referentes a esta película.</p>' ?>

            <div class="pagination_links">
                <button id="prev_button" disabled>Anterior</button>
                <button id="next_button">Siguiente</button>
            </div>

        </div>

    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const comments = document.querySelectorAll('.comment_card');
            const commentsPerPage = 4;
            let currentPage = 1;

            function showPage(page) {
                const start = (page - 1) * commentsPerPage;
                const end = start + commentsPerPage;

                comments.forEach((comment, index) => {
                    if (index >= start && index < end) {
                        comment.style.display = 'flex';
                    } else {
                        comment.style.display = 'none';
                    }
                });

                document.getElementById('prev_button').disabled = (page === 1);
                document.getElementById('next_button').disabled = (end >= comments.length);
            }

            document.getElementById('prev_button').addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            document.getElementById('next_button').addEventListener('click', function () {
                if ((currentPage * commentsPerPage) < comments.length) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            showPage(currentPage);
        });
    </script>