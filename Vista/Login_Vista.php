<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelis Review - Login</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css"href="css/login.css">
    
    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="form_wrapper" id="loginForm">
    <h3 class="form_title">Iniciar sesión</h3>
    
        <form action="" method="POST">

        <div class="input_field">            
            <input type="text" id="username_login" name="username_login" required placeholder="Nombre de usuario:" autocomplete="on">
        </div>

        <div class="input_field">            
            <input type="password" id="password_login" name="password_login" required placeholder="Clave de acceso:">
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
    
        <form action="" method="POST" enctype="multipart/form-data">

        <div class="input_field">            
            <input type="text" id="username_register" name="username_register" required placeholder="Nombre de usuario:" autocomplete="on">
        </div>

        <div class="input_field">            
            <input type="number" id="age_register" name="age_register" required placeholder="Edad:">
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
            <input type="password" id="password_register" name="password_register" required placeholder="Clave de acceso:">
            <span toggle="#password_register" class="fa fa-fw fa-eye field-icon toggle_password"></span>
        </div>

        <div class="input_field">            
            <input type="file" id="pic_register" name="pic_register" accept="image/png, image/jpeg, image/jpg">
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