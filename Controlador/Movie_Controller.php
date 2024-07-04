<?php 
    session_start();

        // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        // Redirigir al Login si no está logueado
        header('Location: index.php?controlador=login');
        exit(); // Terminar la ejecución del script después de la redirección
    }else{
        require_once("Modelo/Movie_Modelo.php");

        function home()
        {

            $movieModel = new Movie_Modelo();
            $error = "";

            $movieId = $_GET['id'];
            $movie = $movieModel->get_movie($movieId);

            $movieGenres = $movieModel->get_movie_genres($movieId);

            $movieComments = $movieModel->get_movie_comments($movieId);

            if($movie == null){
                $error = 'Ha habido un problema al obtener la película';
            }

            console_log($_SESSION);
            console_log($movie);
            console_log($movieComments);
            console_log($movieGenres);

            require_once("Vista/Movie_Vista.php");
        }

    }
?>