<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Catálogo</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="css/papelera.css">
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
                            <div class="delete_actions">
                                <i class="fa-solid fa-trash-can-arrow-up hover_scale_mayor"></i>
                                <i class="fa-solid fa-circle-xmark hover_scale_mayor"></i>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;
                } else {  echo 'No hay películas eliminadas en la base de datos o ha habido algún problema al conectarse'; } ?>
            </tbody>
        </table>
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
</script>
