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
            $limit = 20;
            $offset = ($page - 1) * $limit;

            $movies = new Movies_Modelo();
            $error = "";
    
            $movies = $movies->get_movies($offset, $limit);

            console_log("SESSION");
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
    }
?>