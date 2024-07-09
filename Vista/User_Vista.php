<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Usuario</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/user.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>

    <div class="navigation_bar">
        <?php require_once("header.php") ?>
    </div>
    
    <?php if ($user) { ?>

        <h1>Tus datos</h1>
        <div class="user_data_container" id="user_data">
            <span>Nombre: <?php echo $user['name'] ?></span>
            <span>Edad: <?php echo $user['edad'] ?></span>
            <span>Ocupación: <?php echo $user['ocupacion'] ?></span>
            <span>Género: <?php echo $user['sex'] ?></span>
            <span class="user_data_pic">Foto de perfil: <img src="imagenes_perfil/<?php echo $user['pic'] ?>"/></span>
        </div>
        <button type="button" class="form_button" id="modificar" onclick="toogleForm()">Modifcar datos</button>

        <div class="form_wrapper" id="modifyForm">
            <h1 class="form_title">Modificar datos</h1>
        
            <form action="" method="POST" enctype="multipart/form-data">

            <div class="input_field">            
                <input type="text" id="username_register" name="username_register" required placeholder="Nombre de usuario" autocomplete="on">
            </div>

            <div class="input_field">            
                <input type="number" id="age_register" name="age_register" required placeholder="Edad">
            </div>

            <div class="input_field" style="height: 100%;">            
                <fieldset>
                    <legend>Sexo:</legend>
                    <div class="gender_input">
                        <input type="radio" id="Male" name="gender_register" value="M">
                        <label for="Male">Hombre</label>
                    </div>

                    <div class="gender_input">
                        <input type="radio" id="Female" name="gender_register" value="F">
                        <label for="Female">Mujer</label>
                    </div>
                </fieldset>
            </div>

            <div class="input_field">            
                <select id="ocupation_register" name="ocupation_register" required>
                    <option value="" disabled selected>Selecciona tu ocupación</option>
                    <?php 
                    foreach ($ocupaciones as $ocupacion) {
                        echo "<option value=\"$ocupacion\">$ocupacion</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input_field">            
                <input type="password" id="password_register" name="password_register" required placeholder="Clave de acceso">
                <span toggle="#password_register" class="fa fa-fw fa-eye field-icon toggle_password"></span>
            </div>

            <div class="input_field">            
                <input type="file" id="pic_register" name="pic_register" accept="image/png, image/jpeg, image/jpg">
            </div>
            
            <div>
                <input type="submit" id="register" name="register" value="Modificar datos" class="form_button">
                <button type="button" value="Cancelar" class="form_button" onclick="toogleForm()">Cancelar</button>
            </div>
            </form>
        </div>

    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>

<script>
    function toogleForm() {
        console.log('Pulsado');
        if(document.getElementById('user_data').style.display == 'flex')
        {
            document.getElementById('user_data').style.display = 'none';
            document.getElementById('modificar').style.display = 'none';
            document.getElementById('modifyForm').style.display = 'flex';
        }else{
            document.getElementById('user_data').style.display = 'flex';
            document.getElementById('modificar').style.display = 'block';
            document.getElementById('modifyForm').style.display = 'none';
        }
    }

    $(".toggle_password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
    input.attr("type", "text");
    } else {
    input.attr("type", "password");
    }
    });
</script>