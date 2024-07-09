<?php
    session_start();
    require_once("Modelo/Users_Modelo.php");

        function home()
        {

            $userModelo = new Users_Modelo();
            $error = "";
            
            $userId = $_SESSION['user_id'];
            $user = $userModelo->get_user($userId);
            $ocupaciones = $userModelo->get_ocupations();

            if($user == null)
            {
                $error = 'Algo ha salido mal al intentar recuperar al usuario de la base de datos';
            }


            console_log('USUARIO');
            console_log($user);
            require_once("Vista/User_Vista.php");
        }

?>