<?php 
    session_start();

    require_once("Modelo/Movies_Modelo.php");

    function home()
    {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $genre = isset($_GET['genre']) && $_GET['genre'] !== '' ? (int)$_GET['genre'] : null;
            $order = isset($_GET['order']) ? $_GET['order'] : '';

            $limit = 20;
            $offset = ($page - 1) * $limit;

            $movies = new Movies_Modelo();
            $error = "";
    
            $catalogue = $movies->get_movies($offset, $limit, $search, $genre, $order);
            $total_results = $movies->get_movie_count($search, $genre);
            $genres = $movies->get_genres();

            foreach($catalogue as $key => $movie) {
                $catalogue[$key]['genres'] = $movies->get_movie_genre($movie['id']);
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
    
            $deleted_movies = $movies->get_deleted_movies($search, $offset, $limit);

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

    function add_movie()
    {
        // Verificar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir los datos del formulario
            $title = $_POST['new_title'];
            $date = $_POST['new_date'];
            $url_imdb = $_POST['new_url_imdb'];
            $description = isset($_POST['new_desc']) ? $_POST['new_desc'] : null;
            $genres = isset($_POST['new_genres']) ? $_POST['new_genres'] : [];

            // Procesar la imagen si se ha subido
            if (isset($_FILES['new_url_pic']) && $_FILES['new_url_pic']['error'] === 0) {
                $uploadDir = 'movies_images/';
                $imageName = basename($_FILES['new_url_pic']['name']);
                $uploadFile = $uploadDir . $imageName;

                // Mover el archivo subido a la carpeta de imágenes
                if (move_uploaded_file($_FILES['new_url_pic']['tmp_name'], $uploadFile)) {
                    $url_pic = $imageName; // Guardar el nombre de la imagen para la base de datos
                } else {
                    $url_pic = null; // En caso de error en la subida
                }
            } else {
                $url_pic = null; // No se subió ninguna imagen
            }

            // Llamar al modelo para insertar la película
            $moviesModel = new Movies_Modelo();
            try{
                $movie_id = $moviesModel->insert_movie($title, $date, $url_imdb, $url_pic, $description);
            }catch(Exception $e){
                $_SESSION['create_error'] = "No se pudo insertar la película.";
                console_log($e);
                require_once("Vista/Admin_Vista.php");
                return;
            }

            // Insertar los géneros seleccionados en la tabla moviegenre
            try{
                if ($movie_id && !empty($genres)) {
                    foreach ($genres as $genre) {
                    $moviesModel->insert_movie_genre($movie_id, $genre);
                    }
                }
            }catch(Exception $e){
                $_SESSION['create_genre_error'] = "No se pudo insertar los géneros.";
                console_log($e);
                require_once("Vista/Admin_Vista.php");
                return;
            }

            // Redirigir a la página de administración o mostrar un mensaje de éxito
            $_SESSION['create_success'] = "Película creada correctamente.";
            header('Location: index.php?controlador=admin&action=home');
            exit();
        }
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
                $_SESSION['update_success'] = "Película actualizada correctamente.";
                header("Location: index.php?controlador=admin&action=home");
            } else {
                // Manejar el error
                $_SESSION['update_error'] = "No se pudo actualizar la película.";
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
            $error = "No se ha podido actualizar la película";
            require_once "Vista/Admin_Vista.php";
        }

    }

    function restore_movie()
    {
        console_log($_GET);
        $movieId = $_GET['movie_id'];

        $movie = new Movies_Modelo();

        $result = $movie->restore_movie($movieId);

        if($result){
            header("Location: index.php?controlador=admin&action=papelera");
        }else{
            $error = "No se ha podido restaurar la película";
            require_once "Vista/Papelera_Vista.php";
        }
    }

    function destroy_movie()
    {
        $movieId = $_GET['movie_id'];

        $movie = new Movies_Modelo();

        $result = $movie->delete_movie_permanently($movieId);

        if($result){
            header("Location: index.php?controlador=admin&action=papelera");
        }else{
            $error = "No se ha podido restaurar la película";
            require_once "Vista/Papelera_Vista.php";
        }
    }

    function destroy_all_movies()
    {
        $movies = new Movies_Modelo();
        $error = "";

        $deleted_movies = $movies->get_deleted_movies_raw();

        console_log($deleted_movies);

        try{
            foreach($deleted_movies as $movie)
            {
                $movies->delete_movie_permanently($movie['id']);
            }

            $_SESSION['destroy_all_success'] = "Películas eliminadas correctamente.";
            header("Location: index.php?controlador=admin&action=papelera");
            exit();
        }catch(Exception $e){
            $_SESSION['destroy_all_error'] = "No se han podido eliminar las películas.";
            require_once "Vista/Papelera_Vista.php";
        }
    }
?>