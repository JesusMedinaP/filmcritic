<?php 
    session_start();

        require_once("Modelo/Movies_Modelo.php");

        function home()
        {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $genre = isset($_GET['genre']) && $_GET['genre'] !== '' ? (int)$_GET['genre'] : null;
            $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

            $limit = 20;
            $offset = ($page - 1) * $limit;

            $movies = new Movies_Modelo();
            $error = "";
    
            $catalogue = $movies->get_movies($offset, $limit, $search, $genre, $order);
            $total_results = $movies->get_movie_count($search, $genre);
            $genres = $movies->get_genres();

            console_log($_SESSION);

            require_once("Vista/Admin_Vista.php");
        }

        function desconectar()
        {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
        }

        function update_movie()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $movie_id = $_POST['movie_id'];
                $title = $_POST['title'];
                $date = $_POST['date'];
                $url_imdb = $_POST['url_imdb'];
                $desc = $_POST['desc'];
                console_log($_POST);
                console_log($_FILES);
        
                // Manejar la subida de la imagen
                if (isset($_FILES['url_pic']) && $_FILES['url_pic']['error'] == 0) {
                    // Define la ruta de destino para guardar la imagen
                    $target_dir = "movies_images/";
                    $target_file = $target_dir . basename($_FILES['url_pic']['name']);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                    // Verificar que es una imagen válida (opcional)
                    $check = getimagesize($_FILES['url_pic']['tmp_name']);
                    if ($check !== false) {
                        // Intentar mover la imagen a la carpeta de destino
                        if (move_uploaded_file($_FILES['url_pic']['tmp_name'], $target_file)) {
                            // La imagen se subió correctamente, actualizar la URL en la base de datos
                            $url_pic = basename($_FILES['url_pic']['name']);
                        } else {
                            // Error al subir la imagen
                            $error = "Hubo un problema subiendo la imagen.";
                            require_once("Vista/Admin_Vista.php");
                            return;
                        }
                    } else {
                        // El archivo no es una imagen
                        $error = "El archivo no es una imagen válida.";
                        require_once("Vista/Admin_Vista.php");
                        return;
                    }
                } else {
                    // No se subió ninguna nueva imagen, mantener la existente
                    $url_pic = $_POST['current_url_pic']; // Este campo debería estar oculto en el formulario con el valor de la imagen actual.
                }
        
                // Actualizar los datos de la película en la base de datos
                $movies = new Movies_Modelo();
                $result = $movies->update_movie($movie_id, $title, $date, $url_imdb, $url_pic, $desc);
        
                if ($result) {
                    // Redirigir a la vista de administrador con éxito
                    header("Location: index.php?controlador=admin&action=home");
                } else {
                    // Manejar el error
                    $error = "No se pudo actualizar la película.";
                    require_once("Vista/Admin_Vista.php");
                }
            }
        }
?>