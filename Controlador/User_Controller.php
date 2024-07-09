<?php
    session_start();
    require_once("Modelo/Users_Modelo.php");

    $userModelo = new Users_Modelo();
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

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['modify'])) {
            $userId = $_SESSION['user_id'];
            $name = !empty($_POST['username_modify']) ? $_POST['username_modify'] : null;
            $age = !empty($_POST['age_modify']) ? $_POST['age_modify'] : null;
            $gender = !empty($_POST['gender_modify']) ? $_POST['gender_modify'] : null;
            $ocupation = !empty($_POST['ocupation_modify']) ? $_POST['ocupation_modify'] : null;
            $password = !empty($_POST['password_modify']) ? md5($_POST['password_modify']) : null;
            $pic = null;
        
            if (!empty($_FILES['pic_modify']['name'])) {
                $pic = $_FILES['pic_modify']['name'];
                move_uploaded_file($_FILES['pic_modify']['tmp_name'], 'imagenes_perfil/' . $pic);
            }
        
            $userModelo->update_user($userId, $name, $age, $gender, $ocupation, $pic, $password);
        }

?>