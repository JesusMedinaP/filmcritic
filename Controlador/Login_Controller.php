<?php 
    session_start();
    require_once("Modelo/Users_Modelo.php");

    function home()
    {
        $login = new Users_Modelo();
        $error = "";

        $ocupaciones = $login->get_ocupations();

        require_once("Vista/Login_Vista.php");
    }

    if (isset($_POST['login'])) {
        console_log($_POST);
    }

    if (isset($_POST['register'])) {
        console_log($_POST);
    }

?>