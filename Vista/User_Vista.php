<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME ?> - Usuario</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/user.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/toast.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <script src="https://kit.fontawesome.com/6ef29524c6.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="js/toast.js"></script>
</head>
<body>

    <?php require_once ("assets/toast.html"); ?>

    <?php if(isset($_SESSION['delete_error'])) echo '<script>showToast("' . $_SESSION['delete_error'] . '", "error");</script>'; unset($_SESSION['delete_error']); ?>

    <div class="navigation_bar">
        <?php require_once("assets/header.php") ?>
    </div>
    
    <?php if ($user) { ?>

        <h1>Tus datos</h1>
        <div class="user_data_container" id="user_data" style="display: flex;">
            <span>Nombre: <span><?php echo $user['name'] ?></span></span>
            <span>Edad: <span><?php echo $user['edad'] ?></span></span>
            <span>Ocupación: <span><?php echo $user['ocupacion'] ?></span></span>
            <span>Género: <span><?php echo $user['sex'] ?></span></span>
            <span class="user_data_pic">Foto de perfil: 
                <?php if($user['pic'] != null): ?>
                <img src="imagenes_perfil/<?php echo $user['pic'] ?>"/>
                <?php else: echo 'No tienes foto de perfil'; ?>
                <?php endif; ?>
            </span>
        </div>
        <div class="button_container">
            <button type="button" class="form_button" id="modificar">Modifcar datos</button>
            <button type="button" class="delete_button" id="eliminar" onclick="openDestroyModal()">Eliminar cuenta</button>
        </div>

        <div class="form_wrapper" id="modifyForm" style="display: none;">
            <h1 class="form_title">Modificar datos</h1>
        
            <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
            <div class="input_field">            
                <input type="text" id="username_modify" name="username_modify" placeholder="Nombre de usuario" autocomplete="on" value="<?php echo $user['name'] ?>">
            </div>

            <div class="input_field">            
                <input type="number" id="age_modify" name="age_modify" placeholder="Edad" value="<?php echo $user['edad'] ?>">
            </div>

            <div class="input_field" style="height: 100%;">            
                <fieldset>
                    <legend>Sexo:</legend>
                    <div class="gender_input">
                        <input type="radio" id="Male" name="gender_modify" value="M" <?php if($user['sex'] == 'M') echo 'checked'; ?>>
                        <label for="Male">Hombre</label>
                    </div>

                    <div class="gender_input">
                        <input type="radio" id="Female" name="gender_modify" value="F" <?php if($user['sex'] == 'F') echo 'checked'; ?>>
                        <label for="Female">Mujer</label>
                    </div>
                </fieldset>
            </div>

            <div class="input_field">            
                <select id="ocupation_modify" name="ocupation_modify">
                    <option value="" disabled>Selecciona tu ocupación</option>
                    <?php 
                    foreach ($ocupaciones as $ocupacion) {
                        echo "<option value=\"$ocupacion\"" . ($ocupacion == $user['ocupacion'] ? ' selected' : '') . ">$ocupacion</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input_field">            
                <input type="password" id="password_modify" name="password_modify" placeholder="Clave de acceso">
                <span toggle="#password_modify" class="fa fa-fw fa-eye field-icon toggle_password"></span>
            </div>

            <div class="input_field">            
                <input type="file" id="pic_modify" name="pic_modify" accept="image/png, image/jpeg, image/jpg">
            </div>
            
            <div style="display: flex; justify-content: space-evenly;">
                <input type="submit" id="modify" name="modify" value="Modificar datos" class="form_button">
                <button type="button" value="Cancelar" id="cancelar" class="form_button">Cancelar</button>
            </div>
            </form>
        </div>

        <!-- Modal de confirmación para destruir usuario -->
        <div id="destroyUserModal" class="destroy_modal">
            <div class="destroy_modal_content">
                <span id="closeDestroyModalButton" class="close" onclick="closeDestroyModal()">&times;</span>
                <div style="text-align: center; column-gap: 10px;">
                    <h2 style="margin-top: 0px;">Confirmar Eliminación</h2>
                    <p>¿Estás seguro de que deseas eliminar permanentemente tu cuenta?</p>
                        <div class="destroy_buttons">
                            <button id="confirmDestroyButton" class="delete-button">Eliminar cuenta</button>
                            <button onclick="closeDestroyModal()" class="cancel_button">Cancelar</button>
                        </div>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <p><?php echo $error ?></p>
    <?php } ?>

</body>
</html>

<script defer>
    document.addEventListener('DOMContentLoaded', function () {
        const modifyButton = document.getElementById('modificar');
        const destroyButton = document.getElementById('eliminar');
        const cancelButton = document.getElementById('cancelar');
        const userData = document.getElementById('user_data');
        const modifyForm = document.getElementById('modifyForm');

        function toggleForm() {
            if (userData.style.display === 'flex') {
                userData.style.display = 'none';
                modifyButton.style.display = 'none';
                destroyButton.style.display = 'none';
                modifyForm.style.display = 'flex';
            } else {
                userData.style.display = 'flex';
                modifyButton.style.display = 'block';
                destroyButton.style.display = 'block';
                modifyForm.style.display = 'none';
            }
        }

        modifyButton.addEventListener('click', toggleForm);
        cancelButton.addEventListener('click', toggleForm);

        $(".toggle_password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });

    // Detectar si se pulsa la tecla Escape para cerrar el modal
    window.addEventListener('keydown', closeOnEscape);

    function closeOnEscape(event) {
        if (event.key === 'Escape') {
            closeDestroyModal();
        }
    }

    function openDestroyModal()
    {
        document.getElementById("destroyUserModal").style.display = "flex"
        // Desactivar el scroll en la página principal
    }

    // Acción para confirmar la eliminación
    document.getElementById("confirmDestroyButton").addEventListener("click", function() {
        // Aquí haces la petición para eliminar la película
        window.location.href = `index.php?controlador=user&action=destroy_user`;
    closeDestroyModal();  // Cierra el modal después de la eliminación
    });

    function closeDestroyModal()
    {
        document.getElementById("destroyUserModal").style.display = "none";
    }
</script>