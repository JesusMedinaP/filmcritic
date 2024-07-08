<?php 
    session_start();

        require_once("Modelo/Movie_Modelo.php");

        function home()
        {

            $movieModel = new Movie_Modelo();
            $error = "";
            $movieId = $_GET['id'];
            $movie = $movieModel->get_movie($movieId);
            
            $movieGenres = $movieModel->get_movie_genres($movieId);
            
            $movieComments = $movieModel->get_movie_comments($movieId);
            
            if(isset($_SESSION['user_id']))
            {
                $userId = $_SESSION['user_id'];
                $movieScore = $movieModel->get_user_score($userId, $movieId);
                console_log("PUNTUACIÓN");
                console_log($movieScore);
            }

            if($movie == null){
                $error = 'Ha habido un problema al obtener la película';
            }

            console_log($_GET);

            console_log("SESSION");
            console_log($_SESSION);

            console_log("Movie");
            console_log($movie);

            console_log("COMENTARIOS");
            console_log($movieComments);

            console_log("Géneros");
            console_log($movieGenres);


            require_once("Vista/Movie_Vista.php");
        }

        function submit_score()
        {
            $movieModel = new Movie_Modelo();
            $userId = $_SESSION['user_id'];
            $movieId = $_POST['movie_id'];
            $score = $_POST['score'];

            $movieModel->submit_score($userId, $movieId, $score);
            header('Location: index.php?controlador=movie&id=' . $movieId);
        }

        function submit_comment()
        {
            $movieModel = new Movie_Modelo();
            $userId = $_SESSION['user_id'];
            $movieId = $_POST['movie_id'];
            $comment = $_POST['comment'];

            $movieModel->submit_comment($userId, $movieId, $comment);
            header('Location: index.php?controlador=movie&id=' . $movieId);
        }

?>