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

            require_once("Vista/Catalogue_Vista.php");
        }

        function desconectar()
        {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
        }
?>