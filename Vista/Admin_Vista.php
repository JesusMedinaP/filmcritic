<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME ?> - Admin</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/validate.css">
    <link rel="stylesheet" type="text/css" href="css/toast.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="js/admin.js"></script>
    <script src="js/toast.js"></script>
</head>
<body>

    <?php require_once ("assets/toast.html"); ?>

    <?php if(isset($_SESSION['create_success'])) echo '<script>showToast("' . $_SESSION['create_success'] . '", "success");</script>'; unset($_SESSION['create_success']); ?>
    <?php if(isset($_SESSION['create_error'])) echo '<script>showToast("' . $_SESSION['create_error'] . '", "error");</script>'; unset($_SESSION['create_error']); ?>
    <?php if(isset($_SESSION['create_genre_error'])) echo '<script>showToast("' . $_SESSION['create_genre_error'] . '", "error");</script>'; unset($_SESSION['create_genre_error']); ?>

    <?php if(isset($_SESSION['update_success'])) echo '<script>showToast("' . $_SESSION['update_success'] . '", "success");</script>'; unset($_SESSION['update_success']); ?>
    <?php if(isset($_SESSION['update_error'])) echo '<script>showToast("' . $_SESSION['update_error'] . '", "error");</script>'; unset($_SESSION['update_error']); ?>

    <?php if(isset($_SESSION['delete_success'])) echo '<script>showToast("' . $_SESSION['delete_success'] . '", "success");</script>'; unset($_SESSION['delete_success']); ?>
    <?php if(isset($_SESSION['delete_error'])) echo '<script>showToast("' . $_SESSION['delete_error'] . '", "error");</script>'; unset($_SESSION['delete_error']); ?>

    <?php if(isset($_SESSION['delete_user_success'])) echo '<script>showToast("' . $_SESSION['delete_user_success'] . '", "success");</script>'; unset($_SESSION['delete_user_success']); ?>
    <?php if(isset($_SESSION['delete_user_error'])) echo '<script>showToast("' . $_SESSION['delete_user_error'] . '", "error");</script>'; unset($_SESSION['delete_user_error']); ?>

    
    <div class="navigation_bar">
        <?php require_once("assets/header.php") ?>
        <div class="search_bar">
            <form method="GET" action="index.php">
                <input type="hidden" name="controlador" value="admin">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar películas...">
                <button type="submit">
                <i class="fa fa-search"></i>
                </button>
            </form>

           <?php echo '<p class="total_results">' . $total_results . ' resultados.</p>'?>
        </div>
        <div class="user_menu">
            <?php if(isset($_SESSION['user_pic']) &&  $_SESSION['user_pic'] != "") {?>
                    <img onclick=togglePopup() class="hover_scale user_pic" src="imagenes_perfil/<?php echo $_SESSION['user_pic'] ?>"/>
            <?php }else echo '<i class="fa fa-user user_icon hover_scale" onclick=togglePopup()></i>' ?>
            <div id="userPopup" class="popup">
                <?php if (isset($_SESSION["user_id"])) { ?>
                    <a href="index.php?controlador=user&action=home">Mi cuenta</a>
                    <a href="index.php?controlador=user&action=perfil">Mi Perfil</a>
                    <a href="index.php?controlador=catalogue&action=home">Catálogo</a>
                    <a href="index.php?controlador=admin&action=papelera">Papelera</a>
                    <a href="index.php?controlador=catalogue&action=desconectar">Desconectar</a>
                <?php } else { ?>
                    <a href="index.php?controlador=login">Iniciar sesión</a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="tab_container">
        <button id="moviesTab" class="tab_button active" onclick="showTab('movies')">Películas</button>
        <button id="usersTab" class="tab_button" onclick="showTab('users')">Usuarios</button>
        <div id="activeLine" class="tab_active_line"></div>
    </div>

    <div id="movies" class="tab_content">
    <?php
    if(!empty($catalogue))
        { ?>
        
        <div class="filters_container">
            <div class="filters">
                <span>Filtros: </span>
                <div class="genre_filter">
                    <?php require_once "assets/filters_admin.html"; ?>
                </div>
            </div>
        </div>

        <?php echo '<p class="total_results_mobile">' . $total_results . ' resultados.</p>'?>

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

            <div>
                <button onclick="openAddModal()" class="add_button hover_scale"><i class="fa-solid fa-plus"></i> Nueva película</button>
            </div>

            <?php foreach($catalogue as $movie)
            {
                // Ruta de la imagen desde la base de datos
                $imagePath = "movies_images/" . $movie['url_pic'];

                // Comprobar si la imagen existe
                if (!file_exists($imagePath) || empty($movie['url_pic'])) {
                    // Si no existe, usar imagen placeholder
                    $imagePath = "movies_images/movie_placeholder.png";
                }

                $movieJson = json_encode(array_merge($movie, ["url_pic" => $imagePath]), JSON_HEX_APOS | JSON_HEX_QUOT);
                ?>
                <div class="movie">
                    <a href="index.php?controlador=movie&id=<?php echo $movie['id'];?>" class="movie_picture hover_scale_minor">
                    <img class="movie_picture hover_scale_minor" src="<?php echo $imagePath ?>" alt="<?php echo $movie['title'] ?>" onerror="this.onerror=null; this.src='movies_images/movie_placeholder.png';"/>
                    </a>
                    <h2 class="movie_title hover_scale"><a href="index.php?controlador=movie&id=<?php echo $movie['id']; ?>"><?php echo $movie['title']; ?></a></h2>
                    <p class="movie_score">
                        <?php if(isset($movie['avg_score'])){ ?>
                        <i class="fa-solid fa-star"></i> <span class="score"><?php echo number_format($movie['avg_score'], 1); ?></span> <span class="score_count"> (<span class="score"><?php echo $movie['score_count']; ?></span> votos)</span>
                        <?php }else echo '<i class="fa-solid fa-star"></i> <span class="score">0.0</span> <span class="score_count"> (<span class="score">0</span> votos)</span>' ?>
                    </p>
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
                        <i onclick='openEditModal(<?php echo $movieJson; ?>)' class="fa-solid fa-pen-to-square hover_scale_mayor"></i>
                        <i onclick="openDeleteModal(<?php echo $movie['id']; ?>)" class="fa-solid fa-trash hover_scale_mayor"></i>
                    </div>
                </div>
            <?php
            } ?>
        </div>

        <!-- Modal para la creación -->
        <div id="addMovieModal" class="modal">
            <div class="modal_content">
                <span class="close" onclick="closeAddModal()">&times;</span>
                <h2>Nueva Película</h2>
                <form id="addMovieForm" method="POST" enctype="multipart/form-data" action="index.php?controlador=admin&action=add_movie" onsubmit="return validateCreate()">

                    <label for="new_title">Título</label>
                    <input type="text" name="new_title" id="new_title">
                    <span class="error_message" id="error_title_add"></span>    
                    
                    <label for="new_date">Fecha de estreno</label>
                    <input type="date" name="new_date" id="new_date">
                    <span class="error_message" id="error_date_add"></span>
                    
                    <label for="new_url_imdb">URL IMDB</label>
                    <input type="url" name="new_url_imdb" id="new_url_imdb">
                    <span class="error_message" id="error_url_imdb_add"></span>
                    
                    <label for="new_url_pic">Imagen</label>
                    <input type="file" name="new_url_pic" id="new_url_pic">
                    
                    <label for="new_desc">Descripción</label>
                    <textarea name="new_desc" id="new_desc" rows="4" placeholder="Añade un descripción"></textarea>

                    <label for="new_genreCheckboxes">Géneros</label>
                    <div id="new_genreCheckboxes" class="genreCheckboxes">
                        <?php foreach ($genres as $genre): ?>
                            <div>
                                <input type="checkbox" name="new_genres[]" value="<?php echo $genre['id']; ?>" id="new_genre_<?php echo $genre['id']; ?>">
                                <label for="new_genre_<?php echo $genre['id']; ?>"><?php echo $genre['name']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit">Añadir película</button>
                </form>
            </div>
        </div>

        <!-- Modal para edición -->
        <div id="editMovieModal" class="modal">
            <div class="modal_content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Editar Película</h2>
                <form id="editMovieForm" method="POST" enctype="multipart/form-data" action="index.php?controlador=admin&action=update_movie" onsubmit="return validateEdit()">
                    <input type="hidden" name="movie_id" id="movie_id">
                    <input type="hidden" name="current_url_pic" id="current_url_pic">
                    
                    <label for="title">Título</label>
                    <input type="text" name="title" id="title">
                    <span class="error_message" id="error_title_edit"></span>
                    
                    <label for="date">Fecha de estreno</label>
                    <input type="date" name="date" id="date">
                    <span class="error_message" id="error_date_edit"></span>
                    
                    <label for="url_imdb">URL IMDB</label>
                    <input type="url" name="url_imdb" id="url_imdb">
                    <span class="error_message" id="error_url_imdb_edit"></span>
                    
                    <label for="url_pic">Imagen</label>
                    <input type="file" name="url_pic" id="url_pic">
                    
                    <label for="desc">Descripción</label>
                    <textarea name="desc" id="desc" rows="4" placeholder="Añade un descripción"></textarea>

                    <label for="genreCheckboxes">Géneros</label>
                    <div id="genreCheckboxes" class="genreCheckboxes">
                        <?php foreach ($genres as $genre): ?>
                            <div>
                                <input type="checkbox" name="genres[]" value="<?php echo $genre['id']; ?>" id="genre_<?php echo $genre['id']; ?>">
                                <label for="genre_<?php echo $genre['id']; ?>"><?php echo $genre['name']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar película -->
        <div id="deleteMovieModal" class="delete_modal">
            <div class="delete_modal_content">
                <span id="closeDeleteModalButton" class="close" onclick="closeDeleteModal()">&times;</span>
                <div style="text-align: center; column-gap: 10px;">
                    <h2 style="margin-top: 0px;">Confirmar Eliminación</h2>
                    <p>¿Estás seguro de que deseas eliminar esta película?</p>
                    <div class="delete_buttons">
                        <button id="confirmDeleteButton" class="delete-button">Eliminar</button>
                        <button onclick="closeDeleteModal()" class="cancel_button">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>


    <?php
        }else echo 'No hay películas en la base de datos o ha habido algún problema al conectarse'; 
    ?>
    </div>


    
    <?php if(!empty($users)){ ?>
        <div id="users" class="tab_content" style="display: none;">
            <div class="search_bar search_users">
                <form id="userSearchForm" onsubmit="return false;">
                        <input
                            type="text"
                            id="userSearchInput"
                            placeholder="Buscar usuario..."
                            onkeyup="searchUsers(this.value, event)"
                        />
                        <button type="button" onclick="searchUsers(document.getElementById('userSearchInput').value, event)">
                            <i class="fa fa-search"></i>
                        </button>
                </form>
            </div>
            <table class="users_table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuario</th>
                    <th>Edad</th>
                    <th>Ocupación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="users_table_body">
                <?php foreach($users as $user): ?>
                    <tr class="user-row">
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['edad']); ?></td>
                        <td><?php echo htmlspecialchars($user['ocupacion']); ?></td>
                        <td>
                            <!-- <i onclick="deleteUser(<?php //echo $user['id']; ?>)" class="fa-solid fa-trash hover_scale_mayor"></i> -->
                            <i onclick="openDestroyUserModal(<?php echo $user['id']; ?>)" class="fa-solid fa-trash hover_scale_mayor"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination_links" style="gap: 20px; justify-content: center;">
            <button onclick="previousPage()" id="prevButton" class="pagination-button">Anterior</button>
            <span id="pageInfo"></span>
            <button onclick="nextPage()" id="nextButton" class="pagination-button">Siguiente</button>
        </div>
    </div>
    <?php }else{ echo 'No hay usuarios en la base de datos';} ?>

    <!-- Modal de confirmación para destruir usuario -->
    <div id="destroyUserModal" class="destroy_modal">
        <div class="destroy_modal_content">
            <span id="closeDestroyModalButton" class="close" onclick="closeDestroyUserModal()">&times;</span>
            <div style="text-align: center; column-gap: 10px;">
                <h2 style="margin-top: 0px;">Confirmar Eliminación</h2>
                <p>¿Estás seguro de que deseas eliminar permanentemente la cuenta del usuario?</p>
                    <div class="destroy_buttons">
                        <button id="confirmDestroyButton" class="delete-button">Eliminar usuario</button>
                        <button onclick="closeDestroyUserModal()" class="cancel_button">Cancelar</button>
                    </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>

    // Inicializar la posición de la línea activa
    document.addEventListener('DOMContentLoaded', () => {
        showTab('movies');
    });

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

    function openAddModal()
    {
        document.getElementById("addMovieModal").style.display = "flex";
        document.body.classList.add('no-scroll');
    }

    function closeAddModal()
    {
        // Limpiar mensajes de error
        document.querySelectorAll('.error_message').forEach(error => {
        error.textContent = '';
        });

        document.querySelectorAll('.input-error').forEach(input => {
            input.classList.remove('input-error');
        });

        document.getElementById("addMovieModal").style.display = "none";
        document.body.classList.remove('no-scroll');

        document.removeEventListener('keydown', closeOnEscape);
    }

    // Función para abrir el modal
    function openEditModal(movie) {
        console.log(movie);
        document.getElementById('movie_id').value = movie.id;
        document.getElementById('title').value = movie.title;
        document.getElementById('date').value = movie.date;
        document.getElementById('url_imdb').value = movie.url_imdb;
        document.getElementById('current_url_pic').value = movie.url_pic.split("/")[1];
        document.getElementById('desc').value = movie.desc;

        // Limpiar géneros previamente seleccionados
        document.querySelectorAll('#genreCheckboxes input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        movie.genres.forEach(genreId => {
        document.getElementById('genre_' + genreId).checked = true;
        });
        
        document.getElementById('editMovieModal').style.display = "flex";

        // Desactivar el scroll en la página principal
        document.body.classList.add('no-scroll');

    }

    // Función para cerrar el modal
    function closeEditModal() {
        
        // Limpiar mensajes de error
        document.querySelectorAll('.error_message').forEach(error => {
            error.textContent = '';
        });

        document.querySelectorAll('.input-error').forEach(input => {
            input.classList.remove('input-error');
        });

        const modal = document.getElementById('editMovieModal');
        modal.style.display = 'none';
        document.body.classList.remove('no-scroll');
        // Remover el listener de la tecla Escape
        document.removeEventListener('keydown', closeOnEscape);
    }

    let movieToDelete;

    function openDeleteModal(movie)
    {
        console.log(movie);
        movieToDelete = movie;
        document.getElementById("deleteMovieModal").style.display = "flex"
        // Desactivar el scroll en la página principal
        document.body.classList.add('no-scroll');
    }

    function closeDeleteModal(){
        document.getElementById("deleteMovieModal").style.display = "none";
        document.body.classList.remove('no-scroll');
    }

    // Acción para confirmar la eliminación
    document.getElementById("confirmDeleteButton").addEventListener("click", function() {
        if (movieToDelete) {
            // Aquí haces la petición para eliminar la película
            window.location.href = `index.php?controlador=admin&action=delete_movie&movie_id=${movieToDelete}`;
        }
        closeDeleteModal();  // Cierra el modal después de la eliminación
    });


    function closeOnEscape(event) {
        if (event.key === 'Escape') {
            closeAddModal()
            closeEditModal();
            closeDeleteModal();
            closeDestroyUserModal();
        }
    }

</script>