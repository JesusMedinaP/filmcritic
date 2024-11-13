<?php 
    session_start();

        require_once("Modelo/Movies_Modelo.php");
        require_once("Modelo/Users_Modelo.php");

        function home()
        {
            // Restaurar sesión si no está establecida
            if (!isset($_SESSION['user_id']) && isset($_COOKIE['session_token'])) {
                $user = new Users_Modelo();
                $session_token = $_COOKIE['session_token'];
                $userData = $user->get_user_by_token($session_token);

                if ($userData) {
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['user_name'] = $userData['name'];
                    $_SESSION['user_pic'] = $userData['pic'];
                    $_SESSION['is_admin'] = $userData['is_admin'];
                }
            }

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
            
            console_log($catalogue);
            console_log($_SESSION);

            require_once("Vista/Catalogue_Vista.php");
        }

        function desconectar()
        {
        session_unset();
        session_destroy();

        // Eliminar la cookie de sesión
        setcookie('session_token', '', time() - 3600, "/", "", true, true);

        header("Location: index.php");
        exit();
        }
?>