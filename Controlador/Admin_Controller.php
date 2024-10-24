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

            foreach($catalogue as &$movie)
            {
                $movie['genres'] = $movies->get_movie_genre($movie['id']);
            }

            console_log($catalogue);

            console_log($_SESSION);

            require_once("Vista/Admin_Vista.php");
    }

    function papelera()
    {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $genre = isset($_GET['genre']) && $_GET['genre'] !== '' ? (int)$_GET['genre'] : null;
            $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

            $limit = 20;
            $offset = ($page - 1) * $limit;

            $movies = new Movies_Modelo();
            $error = "";
    
            $deleted_movies = $movies->get_deleted_movies($offset, $limit);

            $total_results = $movies->get_deleted_movie_count($search, $genre);

            console_log($deleted_movies);

            console_log($_SESSION);

            require_once("Vista/Papelera_Vista.php");
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
                $genres = $_POST['genres'];  // Array de géneros seleccionados
                console_log($_POST);
        
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
                $resultUpdate = $movies->update_movie($movie_id, $title, $date, $url_imdb, $url_pic, $desc);

                // Actualizar géneros asociados
                $resultGenre = $movies->update_movie_genres($movie_id, $genres);

                if ($resultUpdate && $resultGenre) {
                    // Redirigir a la vista de administrador con éxito
                    header("Location: index.php?controlador=admin&action=home");
                } else {
                    // Manejar el error
                    $error = "No se pudo actualizar la película.";
                    console_log($error);
                    require_once("Vista/Admin_Vista.php");
                }
        }
    }

    function delete_movie()
    {
        console_log($_GET);
        $movieId = $_GET['movie_id'];
        
        $movie = new Movies_Modelo();

        $result = $movie->soft_delete_movie($movieId);

        if($result)
        {
            header("Location: index.php?controlador=admin&action=home");
        }else{
            $error = "No se puedo actualizar la película";
            require_once("Vista/Admin_Vista.php");
        }

    }
?>