<?php 
    session_start();
    require_once("Modelo/Users_Modelo.php");

    function home()
    {
        $user = new Users_Modelo();
        $error = "";

        $ocupaciones = $user->get_ocupations();

        require_once("Vista/Login_Vista.php");
    }


    if (isset($_POST['login'])) {
        console_log($_POST);
    }


    if (isset($_POST['register'])) {
        console_log($_POST);
        console_log($_FILES);
        
        $nombre = isset($_POST['username_register']) ? $_POST['username_register'] : '';
        $edad = isset($_POST['age_register']) ? $_POST['age_register'] : '';
        $gender = isset($_POST['gender_register']) ? $_POST['gender_register'] : '';
        $ocupacion = isset($_POST['ocupation_register']) ? $_POST['ocupation_register'] : '';
        $password = isset($_POST['password_register']) ? md5($_POST['password_register']) : '';
        
        if(isset($_POST['pic_register']))
        {
            $target_dir = "imagenes_perfil/";
            $target_file = $target_dir . basename($_FILES["pic_register"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["pic_register"]["tmp_name"]);
            if($check !== false) {
                console_log("El archivo es una imagen - " . $check["mime"] . ".");
                $uploadOk = 1;
            } else {
                console_log('El archivo no es una imagen');
                $uploadOk = 0;
            }
            
            // Check if file already exists
            if (file_exists($target_file)) {
                console_log("El archivo ya existe");
                $uploadOk = 0;
            }
            
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                console_log("Los formatos admitidos son: JPG, JPEG, PNG.");
                $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "No se ha podido subir el archivo";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["pic_register"]["tmp_name"], $target_file)) {
                console_log("The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.");
                } else {
                console_log("Sorry, there was an error uploading your file.");
                }
            }
        }else{
            $pic = '';
        }

        if($user->register())
        {
            $error = "Insertado correctamente";
        }else $error = "Error al insertar";
        console_log($error);
    }

?>