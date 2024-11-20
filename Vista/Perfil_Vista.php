<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME ?> - Mi perfil</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/user.css">
    <link rel="stylesheet" type="text/css" href="css/perfil.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>
<body>


    <div class="navigation_bar">
        <?php require_once("assets/header.php") ?>
    </div>
    
    <?php if ($user) { ?>
        <h1>Mis contribuciones</h1>
        <?php if (count($userContributions) > 0): ?>
            <div class="contributions_wrapper">
                <button class="scroll_button left" onclick="scrollToLeft()"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="contributions_container">
                    <?php foreach ($userContributions as $movie): ?>
                        <div class="contribution_card">
                            <a href="index.php?controlador=movie&id=<?php echo $movie['movie_id'] ?>" class="hover_scale_minor">
                                <img src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>">
                            </a>
                            <h2 class="movie_title hover_scale"><a href="index.php?controlador=movie&id=<?php echo $movie['movie_id']; ?>"><?php echo $movie['title']; ?></a></h2>
                            
                            <p style="margin-bottom: 0;">Puntuación</p>
                            <!-- Mostrar puntuación si existe -->
                            <?php if ($movie['score'] !== null): ?>
                                <p class="movie_score">
                                    <?php
                                    $score = (int)$movie['score'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $score) {
                                            echo '<i class="fa-solid fa-star rated"></i>';
                                        } else {
                                            echo '<i class="fa-solid fa-star unrated"></i>';
                                        }
                                    }
                                    ?>
                                </p>
                            <?php else: ?>
                                <p class="movie_score">No has puntuado esta película</p>
                            <?php endif; ?>

                            <!-- Mostrar cantidad de comentarios -->
                            <?php if ($movie['user_comments_count'] > 0): ?>
                                <p>Has dejado <span class="total_comments"><?php echo $movie['user_comments_count']; ?></span> comentarios</p>
                            <?php else: ?>
                                <p>No has dejado ningún comentario</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="scroll_button right" onclick="scrollToRight()"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        <?php else: ?>
            <p>No has puntuado ninguna película o dejado ningún comentario.</p>
        <?php endif; ?>
    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>

<script>
    
    function scrollToLeft() {
        const container = document.querySelector('.contributions_container');
        container.scrollBy({ left: -300, behavior: 'smooth' });
        updateScrollButtons();
    }
    
    function scrollToRight() {
        const container = document.querySelector('.contributions_container');
        container.scrollBy({ left: 300, behavior: 'smooth' });
        updateScrollButtons();
    }

    function updateScrollButtons() {
        const container = document.querySelector('.contributions_container');
        const leftButton = document.querySelector('.scroll_button.left');
        const rightButton = document.querySelector('.scroll_button.right');

        leftButton.disabled = container.scrollLeft === 0;
        rightButton.disabled = container.scrollLeft + container.clientWidth >= container.scrollWidth;
    }

    function updateScrollButtons() {
    const container = document.querySelector('.contributions_container');
    const leftButton = document.querySelector('.scroll_button.left');
    const rightButton = document.querySelector('.scroll_button.right');

    const scrollLeft = Math.ceil(container.scrollLeft);
    const maxScrollLeft = Math.ceil(container.scrollWidth - container.clientWidth);

    leftButton.disabled = scrollLeft === 0;
    rightButton.disabled = scrollLeft >= maxScrollLeft;
    }

    document.querySelector('.contributions_container').addEventListener('scroll', updateScrollButtons);
    updateScrollButtons(); // Inicializa el estado de los botones
</script>