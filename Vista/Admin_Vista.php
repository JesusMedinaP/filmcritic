<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Catálogo</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
</head>
<body>
    
    <div class="navigation_bar">
        <?php require_once("header.php") ?>
        <div class="search_bar">
            <form method="GET" action="index.php">
                <input type="hidden" name="controlador" value="admin">
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
                    <a href="index.php?controlador=catalogue&action=home">Catálogo</a>
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
                <!-- Formulario de Géneros -->
                <form method="GET" action="index.php">
                    <input type="hidden" name="controlador" value="admin">
                    <select name="genre" onchange="this.form.submit()">
                        <option value="">Todos los géneros</option>
                        <?php foreach ($genres as $g): ?>
                            <option value="<?php echo $g['id']; ?>" <?php if ($g['id'] == $genre) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($g['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Orden alfabético -->
                    <select name="order" id="order" onchange="this.form.submit()">
                        <option value="ASC" <?php echo (isset($_GET['order']) && $_GET['order'] == 'ASC') ? 'selected' : ''; ?>>ASC</option>
                        <option value="DESC" <?php echo (isset($_GET['order']) && $_GET['order'] == 'DESC') ? 'selected' : ''; ?>>DESC</option>
                    </select>
                </form>
            </div>

            <div class="rate_filter">

            </div>
        </div>
    </div>

    <div class="pagination_links">
            <form method="GET" action="index.php">
                <input type="hidden" name="controlador" value="admin">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                
                <button type="submit" name="page" value="<?php echo $page - 1; ?>" <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Anterior</button>
                <button type="submit" name="page" value="<?php echo $page + 1; ?>" <?php echo (count($catalogue) < $limit) ? 'disabled' : ''; ?>>Siguiente</button>
            </form>
    </div>

    <div class="movies_container">
        <?php foreach ($catalogue as $movie)
        {?>
            <div class="movie">
                <a href="index.php?controlador=movie&id=<?php echo $movie['id']; ?>" class="movie_picture hover_scale_minor">
                    <img class="movie_picture hover_scale_minor" src="movies_images/<?php echo $movie['url_pic'] ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                </a>
                <h2 class="movie_title hover_scale"><a href="index.php?controlador=movie&id=<?php echo $movie['id']; ?>"><?php echo $movie['title']; ?></a></h2>
                <p class="movie_score"><i class="fa-solid fa-star"></i> <span class="score"><?php echo number_format($movie['avg_score'], 1); ?></span> (<span class="score"><?php echo $movie['score_count']; ?></span> votos)</p>
                <div class="movie_description_container">
                    <p class="movie_description"> 
                        <?php 
                            if($movie['desc'] != "" && $movie['desc'] != "N/A") echo ($movie['desc']);
                            else echo 'No hay descripción para esta película';
                            ?>
                    </p>
                </div>
                <h3 class="movie_date"><?php echo $movie['date'] ?></h3>
                <div class="actions_container">
                <i onclick='openEditModal(<?php echo json_encode($movie); ?>)' class="fa-solid fa-pen-to-square hover_scale"></i>
                    <i class="fa-solid fa-trash hover_scale"></i>
                </div>
            </div>
        <?php
        } ?>
    </div>


    <!-- Modal para edición -->
    <div id="editMovieModal" class="modal">
        <div class="modal_content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Editar Película</h2>
            <form id="editMovieForm" method="POST" action="index.php?controlador=admin&action=update_movie">
            <input type="hidden" name="movie_id" id="movie_id">
            
            <label for="title">Título</label>
            <input type="text" name="title" id="title" required>
            
            <label for="date">Fecha</label>
            <input type="date" name="date" id="date" required>
            
            <label for="url_imdb">URL IMDB</label>
            <input type="url" name="url_imdb" id="url_imdb" required>
            
            <label for="url_pic">URL Imagen</label>
            <input type="text" name="url_pic" id="url_pic" required>
            
            <label for="desc">Descripción</label>
            <textarea name="desc" id="desc" rows="4" required></textarea>
            
            <button type="submit">Guardar Cambios</button>
            </form>
        </div>
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

    // Función para abrir el modal
    function openEditModal(movie) {
        console.log(movie);
        document.getElementById('movie_id').value = movie.id;
        document.getElementById('title').value = movie.title;
        document.getElementById('date').value = movie.date;
        document.getElementById('url_imdb').value = movie.url_imdb;
        document.getElementById('url_pic').value = movie.url_pic;
        document.getElementById('desc').value = movie.desc;
        
        document.getElementById('editMovieModal').style.display = "flex";
    }

    // Función para cerrar el modal
    function closeModal() {
        document.getElementById('editMovieModal').style.display = "none";
    }

</script>