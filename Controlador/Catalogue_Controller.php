<?php 
    session_start();

        // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        // Redirigir al Login si no está logueado
        header('Location: index.php?controlador=login');
        exit(); // Terminar la ejecución del script después de la redirección
    }else{
        require_once("Modelo/Movies_Modelo.php");

        function home()
        {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $genre = isset($_GET['genre']) && $_GET['genre'] !== '' ? (int)$_GET['genre'] : null;

            $limit = 20;
            $offset = ($page - 1) * $limit;

            $movies = new Movies_Modelo();
            $error = "";
    
            $catalogue = $movies->get_movies($offset, $limit, $search, $genre);
            $total_results = $movies->get_movie_count($search, $genre);
            $genres = $movies->get_genres();

            console_log($_GET);

            require_once("Vista/Catalogue_Vista.php");
        }

        function desconectar()
        {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
        }
    }
?>