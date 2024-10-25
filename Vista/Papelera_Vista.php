<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Catálogo</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="css/papelera.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
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
                        <a href="index.php?controlador=admin&action=papelera">Papelera</a>
                        <a href="index.php?controlador=catalogue&action=desconectar">Desconectar</a>
                    <?php } else { ?>
                        <a href="index.php?controlador=login">Iniciar sesión</a>
                    <?php } ?>
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
                <button type="submit" name="page" value="<?php echo $page + 1; ?>" <?php echo (count($deleted_movies) < $limit) ? 'disabled' : ''; ?>>Siguiente</button>
            </form>
    </div>

    <div class="deleted_container">
        <h2>Películas Eliminadas</h2>
        <table class="deleted_table">
            <thead>
                <tr>
                    <th>Película</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($deleted_movies)){
                    foreach($deleted_movies as $movie):
                    // Ruta de la imagen desde la base de datos
                    $imagePath = "movies_images/" . $movie['url_pic'];

                    // Comprobar si la imagen existe
                    if (!file_exists($imagePath) || empty($movie['url_pic'])) {
                        // Si no existe, usar imagen placeholder
                        $imagePath = "movies_images/movie_placeholder.png";
                    }
                    $movieJson = json_encode(array_merge($movie, ["url_pic" => $imagePath]), JSON_HEX_APOS | JSON_HEX_QUOT);
                    ?>
                    <tr>
                        <td class="deleted_movie_info">
                            <a href="index.php?controlador=movie&id=<?php echo $movie['id'];?>" class="movie_picture hover_scale_minor">
                            <img class="movie_picture hover_scale_minor" src="<?php echo $imagePath ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                            </a>
                            <h2 class="movie_title hover_scale"><?php echo $movie['title']; ?></h2>
                        </td>
                        <td>
                            <!--<button onclick="restoreMovie(<?php echo $movie['id']; ?>)"><i class="fa-solid fa-trash-can-arrow-up"></i></button>
                            <button onclick="deleteMoviePermanently(<?php echo $movie['id']; ?>)"><i class="fa-solid fa-circle-xmark"></i></button>
                            -->
                            <div class="actions">
                                <i onclick="openRestoreModal(<?php echo $movie['id']; ?>)" class="fa-solid fa-trash-can-arrow-up hover_scale_mayor"></i>
                                <i onclick="openDestroyModal(<?php echo $movie['id']; ?>)" class="fa-solid fa-circle-xmark hover_scale_mayor"></i>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;
                } else {  echo 'No hay películas eliminadas en la base de datos o ha habido algún problema al conectarse'; } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de confirmación para restaurar película -->
    <div id="restoreMovieModal" class="restore_modal">
        <div class="restore_modal_content">
            <span id="closeRestoreModalButton" class="close" onclick="closeRestoreModal()">&times;</span>
            <div style="text-align: center; column-gap: 10px;">
                <h2 style="margin-top: 0px;">Confirmar Restauración</h2>
                <p>¿Estás seguro de que deseas restaurar esta película?</p>
                <div class="restore_buttons">
                    <button id="confirmRestoreButton" class="restore-button">Restaurar</button>
                    <button onclick="closeRestoreModal()" class="cancel_button">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal de confirmación para destruir película -->
        <div id="destroyMovieModal" class="destroy_modal">
        <div class="destroy_modal_content">
            <span id="closeDestroyModalButton" class="close" onclick="closeDestroyModal()">&times;</span>
            <div style="text-align: center; column-gap: 10px;">
                <h2 style="margin-top: 0px;">Confirmar Eliminación</h2>
                <p>¿Estás seguro de que deseas eliminar permanentemente esta película?</p>
                <div class="destroy_buttons">
                    <button id="confirmDestroyButton" class="delete-button">Restaurar</button>
                    <button onclick="closeDestroyModal()" class="cancel_button">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

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

    // Detectar si se pulsa la tecla Escape para cerrar el modal
    window.addEventListener('keydown', closeOnEscape);

    function closeOnEscape(event) {
        if (event.key === 'Escape') {
            closeRestoreModal();
            closeDestroyModal();
        }
    }

    let selectedMovie;

    function openRestoreModal(movieId)
    {
        selectedMovie = movieId;
        console.log(selectedMovie);
        document.getElementById("restoreMovieModal").style.display = "flex"
        // Desactivar el scroll en la página principal
        document.body.classList.add('no-scroll');
    }

        // Acción para confirmar la eliminación
        document.getElementById("confirmRestoreButton").addEventListener("click", function() {
        if (selectedMovie) {
            // Aquí haces la petición para eliminar la película
            window.location.href = `index.php?controlador=admin&action=restore_movie&movie_id=${selectedMovie}`;
        }
        closeDeleteModal();  // Cierra el modal después de la eliminación
    });

    function closeRestoreModal()
    {
        document.getElementById("restoreMovieModal").style.display = "none";
        document.body.classList.remove('no-scroll');
    }

    function openDestroyModal(movieId)
    {
        console.log(movieId);
        selectedMovie = movieId;
        document.getElementById("destroyMovieModal").style.display = "flex"
        // Desactivar el scroll en la página principal
        document.body.classList.add('no-scroll');
    }

        // Acción para confirmar la eliminación
        document.getElementById("confirmDestroyButton").addEventListener("click", function() {
        if (selectedMovie) {
            // Aquí haces la petición para eliminar la película
            window.location.href = `index.php?controlador=admin&action=destroy_movie&movie_id=${selectedMovie}`;
        }
        closeDestroyModal();  // Cierra el modal después de la eliminación
    });

    function closeDestroyModal()
    {
        document.getElementById("destroyMovieModal").style.display = "none";
        document.body.classList.remove('no-scroll');
    }

</script>
