<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Login</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css"href="css/login.css">
    <link rel="stylesheet" type="text/css" href="css/toast.css">
    <link rel="stylesheet" type="text/css" href="css/validate.css">
    
    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    
    <script src="js/login.js"></script>
    <script src="js/toast.js"></script>
</head>
<body>

    <div class="navigation_bar">
        <?php require_once("header.php") ?>
    </div>

    <?php require_once("assets/toast.html"); ?>

        <?php 
            if(isset($_SESSION['user_exists'])) {
                echo "<script>showToast('" . $_SESSION['user_exists'] . "', 'error');</script>";
                unset($_SESSION['user_exists']);
            }
        ?>

        <?php 
            if(isset($_SESSION['user_registered'])) {
                echo "<script>showToast('" . $_SESSION['user_registered'] . "', 'success');</script>";
                unset($_SESSION['user_registered']);
            }
        ?>

        <?php 
            if(isset($_SESSION['login_failed'])) {
                echo "<script>showToast('" . $_SESSION['login_failed'] . "', 'error');</script>";
                unset($_SESSION['login_failed']);
            }
        ?>

    <div class="form_wrapper" id="loginForm">
    <h1 class="form_title">Iniciar sesión</h1>
    
        <form action="index.php?controlador=login&action=login" method="POST" onsubmit="return validateLogin()">

        <div class="input_field">            
            <input type="text" id="username_login" name="username_login" placeholder="Nombre de usuario" autocomplete="on">
            <span class="error_message" id="error_username_login"></span>
        </div>

        <div class="input_field">            
            <input type="password" id="password_login" name="password_login" placeholder="Clave de acceso">
            <span toggle="#password_login" class="fa fa-fw fa-eye field-icon toggle_password"></span>
            <span class="error_message" id="error_password_login"></span>
        </div>
                
        <input type="submit" id="login" name="login" value="Iniciar sesión" class="form_button">

        <div class="toogle_form">
        <span>¿No tienes una cuenta? <a href="javascript:void(0);" onclick="toggleForm('register')">Registrarse</a></span>
        </div>
        </form>
    </div>

    <div class="form_wrapper" id="registerForm">
    <h3 class="form_title">Registrarse</h3>
    
        <form action="index.php?controlador=login&action=register" method="POST" enctype="multipart/form-data" onsubmit="return validateRegister()">

            <div class="input_field">            
                <input type="text" id="username_register" name="username_register" placeholder="Nombre de usuario" autocomplete="on">
                <span class="error_message" id="error_username"></span>
            </div>

            <div class="input_field">            
                <input type="number" id="age_register" name="age_register" placeholder="Edad">
                <span class="error_message" id="error_age"></span>
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
                <!--<span class="error_message" id="error_gender"></span>-->
            </div>

            <div class="input_field">            
                <select id="ocupation_register" name="ocupation_register">
                    <option value="" disabled selected>Selecciona tu ocupación</option>
                    <?php 
                    foreach ($ocupaciones as $ocupacion) {
                        echo "<option value=\"$ocupacion\">$ocupacion</option>";
                    }
                    ?>
                </select>
                <span class="error_message" id="error_ocupation"></span>
            </div>

            <div class="input_field">            
                <input type="password" id="password_register" name="password_register" placeholder="Clave de acceso">
                <span toggle="#password_register" class="fa fa-fw fa-eye field-icon toggle_password"></span>
                <span class="error_message" id="error_password"></span>
            </div>

            <div class="input_field" id="pic_field">            
                <input type="file" id="pic_register" name="pic_register" accept="image/png, image/jpeg, image/jpg">
                <span class="error_message" id="error_pic"></span>
            </div>
                    
            <input type="submit" id="register" name="register" value="Registrarse" class="form_button">

            <div class="toogle_form">
            <span>¿Ya tienes cuenta? <a href="javascript:void(0);" onclick="toggleForm('login')">Iniciar sesión</a></span>
            </div>
        </form>
    </div>


</body>

<script>

    function toggleForm(formType) {
        if (formType === 'login') {
            document.getElementById('loginForm').style.display = 'flex';
            document.getElementById('registerForm').style.display = 'none';
        } else {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'flex';
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
</html>