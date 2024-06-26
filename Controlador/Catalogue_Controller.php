<?php 
    session_start();

        // Verificar si el usuario está logueado
    if (!isset($_SESSION['user'])) {
        // Redirigir al Login si no está logueado
        header('Location: index.php?controlador=login');
        exit(); // Terminar la ejecución del script después de la redirección
    }else{
        require_once("Modelo/Movies_Modelo.php");

        function home()
        {
            $movies = new Movies_Modelo();
            $error = "";
    
            $movies = $movies->get_movies();
    
            console_log($movies);

            require_once("Vista/Catalogue_Vista.php");
        }
    }
?>