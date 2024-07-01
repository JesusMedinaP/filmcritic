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

            $movie = $movieModel->get_movie($_GET['id']);

            if($movie == null){
                $error = 'Ha habido un problema al obtener la película';
            }

            console_log($movie);

            require_once("Vista/Movie_Vista.php");
        }

    }
?>