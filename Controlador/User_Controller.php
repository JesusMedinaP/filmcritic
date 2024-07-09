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
            console_log($_POST);
            console_log($_FILES);

            $userId = $_SESSION['user_id'];
            $name = !empty($_POST['username_modify']) ? $_POST['username_modify'] : null;
            $age = !empty($_POST['age_modify']) ? $_POST['age_modify'] : null;
            $gender = !empty($_POST['gender_modify']) ? $_POST['gender_modify'] : null;
            $ocupation = !empty($_POST['ocupation_modify']) ? $_POST['ocupation_modify'] : null;
            $password = !empty($_POST['password_modify']) ? md5($_POST['password_modify']) : null;
            $pic = null;
        
            /*if (!empty($_FILES['pic_modify']['name'])) {
                $pic = $_FILES['pic_modify']['name'];
                move_uploaded_file($_FILES['pic_modify']['tmp_name'], 'imagenes_perfil/' . $pic);
            }*/

            if(isset($_FILES['pic_modify']) && !empty($_FILES['pic_modify']['name']))
            {
                $target_dir = "imagenes_perfil/";
                $target_file = $target_dir . $userId . "_" . basename($_FILES["pic_modify"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["pic_modify"]["tmp_name"]);
                if($check !== false) {
                    console_log("El archivo es una imagen - " . $check["mime"] . ".");
                    $uploadOk = 1;
                } else {
                    console_log('El archivo no es una imagen');
                    $uploadOk = 0;
                }
                
                // Check if file already exists
                // if (file_exists($target_file)) {
                //     console_log("El archivo ya existe");
                //     $uploadOk = 0;
                // }
                
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
                    if (move_uploaded_file($_FILES["pic_modify"]["tmp_name"], $target_file)) {
                    console_log("The file ". htmlspecialchars( basename( $_FILES["pic_modify"]["name"])). " has been uploaded.");
                    $pic = $userId . "_" . $_FILES['pic_modify']['name'];
                    } else {
                    console_log("Sorry, there was an error uploading your file.");
                    }
                }
            }else{
                $pic = null;
            }

            console_log($pic);
        
            $userModelo->update_user($userId, $name, $age, $gender, $ocupation, $pic, $password);
        }

?>