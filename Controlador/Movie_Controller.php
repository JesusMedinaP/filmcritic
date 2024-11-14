<?php 
    session_start();

        require_once("Modelo/Movie_Modelo.php");

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
            $_SESSION['submit_success'] = "Puntuación enviada";
            header('Location: index.php?controlador=movie&id=' . $movieId);
        }

        function submit_comment()
        {
            $movieModel = new Movie_Modelo();
            $userId = $_SESSION['user_id'];
            $movieId = $_POST['movie_id'];
            $comment = $_POST['comment'];

            $movieModel->submit_comment($userId, $movieId, $comment);
            $_SESSION['submit_success'] = "Comentario publicado";
            header('Location: index.php?controlador=movie&id=' . $movieId);
        }

        function edit_comment()
        {
            if(isset($_POST['comment_id']) && isset($_POST['comment']) &&  $_POST['movie_id']){
                $commentId = $_POST['comment_id'];
                $comment = $_POST['comment'];
                $movieId = $_POST['movie_id'];

                $movie = new Movie_Modelo();

                $result = $movie->update_comment($commentId, $comment);

                if($result){
                    $_SESSION['edit_success'] = "Comentario actualizado";
                    header('Location: index.php?controlador=movie&id=' . $movieId);
                }else{
                    $_SESSION['edit_error'] = "No se ha podido actualizar el comentario";
                    header('Location: index.php?controlador=movie&id=' . $movieId);
                }
            }else{
                console_log("Datos insuficientes en el formulario");
                header('Location: index.php?');
            }
        }

        function delete_comment()
        {
            console_log("Entré al controlador");
            console_log($_GET);

            if(isset($_GET['comment']) && $_GET['id'])
            {
                $commentId = $_GET['comment'];

                $movieId = $_GET['id'];

                $movie = new Movie_Modelo();

                $result = $movie->delete_comment($commentId);

                if($result){
                    header('Location: index.php?controlador=movie&id=' . $movieId);
                    $_SESSION['delete_success'] = "Comentario eliminado";
                }else{
                    $_SESSION['delete_error'] = "No se ha podido eliminar el comentario";
                    header('Location: index.php?controlador=movie&id=' . $movieId);
                }
            }
        }

?>