<?php 
    session_start();
    require_once("Modelo/Users_Modelo.php");

    function home()
    {
        $user = new Users_Modelo();
        $error = "";

        $ocupaciones = $user->get_ocupations();
        console_log("SESSION");
        console_log( $_SESSION);

        if(isset($_SESSION['user_id']))
        {
            header("Location: index.php");
            exit();
        }

        require_once("Vista/Login_Vista.php");
    }


    if (isset($_POST['login'])) {
        console_log("POST LOGIN");
        console_log($_POST);

        $user = new Users_Modelo();

        $nombre = isset($_POST['username_login']) ? $_POST['username_login'] : '';
        $password = isset($_POST['password_login']) ? md5($_POST['password_login']) : '';

        if($user->login($nombre, $password)){
            header('Location: index.php');
            exit();
        }else{
            $error = "Nombre de usuario o contraseña incorrectos";
            console_log($error);
        }
    }


    if (isset($_POST['register'])) {
        $user = new Users_Modelo();
        
        $nombre = isset($_POST['username_register']) ? $_POST['username_register'] : '';
        $edad = isset($_POST['age_register']) ? $_POST['age_register'] : '';
        $gender = isset($_POST['gender_register']) ? $_POST['gender_register'] : '';
        $ocupacion = isset($_POST['ocupation_register']) ? $_POST['ocupation_register'] : '';
        $password = isset($_POST['password_register']) ? md5($_POST['password_register']) : '';

        $pic = '';
        
        // Primero, registramos al usuario sin la imagen
        if($user->register($nombre, $edad, $gender, $ocupacion, $pic, $password)) {
            $userId = $user->get_last_inserted_id(); // Obtenemos el ID del nuevo usuario

            if (isset($_FILES['pic_register'])) {
                $target_dir = "imagenes_perfil/";
                $imageFileType = strtolower(pathinfo($_FILES["pic_register"]["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . $userId . '_' . basename($_FILES["pic_register"]["name"]);

                $uploadOk = 1;

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["pic_register"]["tmp_name"]);
                if ($check !== false) {
                    console_log("El archivo es una imagen - " . $check["mime"] . ".");
                    $uploadOk = 1;
                } else {
                    console_log('El archivo no es una imagen');
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    console_log("Los formatos admitidos son: JPG, JPEG, PNG.");
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    console_log( "No se ha podido subir el archivo");
                } else {
                    if (move_uploaded_file($_FILES["pic_register"]["tmp_name"], $target_file)) {
                        console_log("The file ". htmlspecialchars( basename( $_FILES["pic_register"]["name"])). " has been uploaded.");
                        $pic = $userId . '_' . $_FILES['pic_register']['name'];

                        // Actualizamos el usuario con la ruta de la imagen
                        $user->update_user_pic($userId, $pic);
                    } else {
                        console_log("Sorry, there was an error uploading your file.");
                    }
                }
            }

            $error = "Insertado correctamente";
        } else {
            $error = "Error al insertar";
        }
        console_log($error);
    }

?>