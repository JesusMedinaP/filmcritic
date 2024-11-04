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
    
    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>

    <div class="navigation_bar">
        <?php require_once("header.php") ?>
    </div>


    <div class="form_wrapper" id="loginForm">
    <h1 class="form_title">Iniciar sesión</h1>
    
        <form action="" method="POST">

        <div class="input_field">            
            <input type="text" id="username_login" name="username_login" placeholder="Nombre de usuario" autocomplete="on">
        </div>

        <div class="input_field">            
            <input type="password" id="password_login" name="password_login" placeholder="Clave de acceso">
            <span toggle="#password_login" class="fa fa-fw fa-eye field-icon toggle_password"></span>
        </div>
                
        <input type="submit" id="login" name="login" value="Iniciar sesión" class="form_button">

        <div class="toogle_form">
        <span>¿No tienes una cuenta? <a href="javascript:void(0);" onclick="toggleForm('register')">Registrarse</a></span>
        </div>
        </form>
    </div>

    <div class="form_wrapper" id="registerForm">
    <h3 class="form_title">Registrarse</h3>
    
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

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

    function validateForm() {
        let isValid = true;

        // Validación del nombre de usuario
        const username = document.getElementById("username_register");
        if (username.value.trim() === "" || username.value.length < 3) {
            document.getElementById("error_username").innerText = "El nombre de usuario debe tener al menos 3 caracteres";
            username.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById("error_username").innerText = "";
            username.classList.remove('input-error');
        }

        // Validación de edad
        const age = document.getElementById("age_register");
        if (age.value < 18 || age.value === "") {
            document.getElementById("error_age").innerText = "Debes ser mayor de 18 años";
            age.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById("error_age").innerText = "";
            age.classList.remove('input-error');
        }

        // Validación de ocupación
        const ocupation = document.getElementById("ocupation_register");
        if (ocupation.value === "") {
            document.getElementById("error_ocupation").innerText = "Selecciona una ocupación";
            ocupation.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById("error_ocupation").innerText = "";
            ocupation.classList.remove('input-error');
        }

        // Validación de contraseña
        const password = document.getElementById("password_register");
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!,.+@#$%^&*])[A-Za-z\d!,.+@#$%^&*]{6,}$/;
        const picInputField = document.getElementById("pic_register").parentElement;

        if (!passwordRegex.test(password.value)) {
            document.getElementById("error_password").innerText = "La clave debe tener al menos 6 caracteres, una mayúscula, un número y un símbolo.";
            password.classList.add('input-error');
            picInputField.classList.add('margin-top-error');
            isValid = false;
        } else {
            document.getElementById("error_password").innerText = "";
            password.classList.remove('input-error');
            picInputField.classList.remove('margin-top-error');
        }

        return isValid;
    }
        
</script>
</html>