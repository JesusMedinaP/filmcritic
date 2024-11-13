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
    
    <div class="navigation_bar">
        <?php require_once("header.php") ?>
        <div class="search_bar">
            <form method="GET" action="">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Barra de búsqueda">
                <button type="submit">
                <i class="fa fa-search"></i>
                </button>
            </form>

           <?php echo '<p>' . $total_results . ' resultados.</p>'?>
        </div>
        <div class="user_menu">
            <?php if(isset($_SESSION['user_pic']) &&  $_SESSION['user_pic'] != "") {?>
                    <img onclick=togglePopup() class="hover_scale user_pic" src="imagenes_perfil/<?php echo $_SESSION['user_pic'] ?>"/>
            <?php }else echo '<i class="fa fa-user user_icon hover_scale" onclick=togglePopup()></i>' ?>
            <div id="userPopup" class="popup">
                <?php if (isset($_SESSION["user_id"])) { ?>
                    <a href="index.php?controlador=user&action=home">Mi cuenta</a>
                    <a href="index.php?controlador=user&action=perfil">Mi Perfil</a>
                    <?php if($_SESSION['is_admin']){
                        echo '<a href="index.php?controlador=admin&action=home">Panel administración</a>';
                    }?>
                    <a href="index.php?controlador=catalogue&action=desconectar">Desconectar</a>
                <?php } else { ?>
                    <a href="index.php?controlador=login">Iniciar sesión</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    if(!empty($catalogue))
    { ?>
    
    <div class="filters_container">
        <div class="filters">
            <span>Filtros: </span>
            <div class="genre_filter">
                <?php require_once("assets/filters.html"); ?>
            </div>
        </div>
    </div>

    <div class="pagination_links">
            <form method="GET" action="index.php">
                <input type="hidden" name="controlador" value="catalogue">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                
                <button type="submit" name="page" value="<?php echo $page - 1; ?>" <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Anterior</button>
                <button type="submit" name="page" value="<?php echo $page + 1; ?>" <?php echo (count($catalogue) < $limit) ? 'disabled' : ''; ?>>Siguiente</button>
            </form>
        </div>

    <div class="movies_container">
        <?php foreach ($catalogue as $movie)
        { ?>
            <div class="movie">
                <a href="index.php?controlador=movie&id=<?php echo $movie['id']; ?>" class="movie_picture hover_scale_minor">
                    <img class="movie_picture hover_scale_minor" src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                </a>
                <h2 class="movie_title hover_scale"><a href="index.php?controlador=movie&id=<?php echo $movie['id']; ?>"><?php echo $movie['title']; ?></a></h2>
                <p class="movie_score"><i class="fa-solid fa-star"></i> <span class="score"><?php echo number_format($movie['avg_score'], 1); ?></span> (<span class="score"><?php echo $movie['score_count']; ?></span> votos)</p>
                <p class="movie_description"> 
                    <?php 
                        if($movie['desc'] != "" && $movie['desc'] != "N/A") echo truncateText($movie['desc']);
                        else echo 'No hay descripción para esta película';
                        ?>
                </p>
                <h3 class="movie_date"><?php echo $movie['date'] ?></h3>
            </div>
        <?php 
        } ?>
    </div>
    <?php
        }else echo 'No hay películas en la base de datos o ha habido algún problema al conectarse'; 
    ?>

</body>
</html>

<script>
    function togglePopup() {
            var popup = document.getElementById("userPopup");
            popup.classList.toggle("active");
        }
        // Close the popup if clicked outside
        window.onclick = function(event) {
            var popup = document.getElementById("userPopup");
            if (!event.target.matches('.user_icon, .user_pic')) {
                if (popup.classList.contains('active')) {
                    popup.classList.remove('active');
                }
            }
        }
</script>

<?php
function truncateText($text, $maxLength = 50) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    } else {
        return $text;
    }
}
?>