function showToast(mensaje, tipo) {
    let toast;
    if(tipo === "success"){
        toast = document.getElementById('toastSuccess');
    }else{
        toast = document.getElementById('toastError');
    }

    const span = toast.querySelector("span");
    span.innerText = mensaje;
    toast.classList.add('active');
    
    setTimeout(() => {
        toast.classList.remove('active');
    }, 3000);
}

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

    if(!isValid){
        return false;
    }

    /*const form = document.querySelector('#registerForm form');
    const formData = new FormData(document.querySelector('#registerForm form'));
    console.log("Form",formData);
    // Depuración de los datos del formulario
    console.log("Contenido del FormData:");
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
        }

    fetch("index.php?controlador=login&action=register", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
    })
    .then(response => {
            const contentType = response.headers.get('content-type');
            console.log("Content Type", contentType);
            console.log("Response", response);
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // Si no es JSON, manejamos el error
                throw new TypeError('La respuesta no es JSON');
            }
        return response.json();
    })
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.success) {
            showToast(data.message, 'success');
            form.reset();
            setTimeout(() => {
                window.location.href = "index.php?controlador=login&action=home";
            }, 3000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        //showToast('Error en el servidor', 'error');
    });*/

    return true; // Prevenir el envío normal del formulario
}