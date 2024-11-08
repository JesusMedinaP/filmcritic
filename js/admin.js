function validateEdit()
{
    let isValid = true;

    const title = document.getElementById("title");
    const urlImdb = document.getElementById("url_imdb");
    const date = document.getElementById("date");

    console.log("Los datos");
    console.log(title.value);
    console.log(urlImdb.value);
    console.log(date.value);

    if(date.value === ""){
        console.log("Es vacía");
    }

    if(title.value.trim() === ""){
        document.getElementById("error_title_edit").innerText = "El título es obligatorio";
        title.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_title_edit").innerText = "";
        title.classList.remove('input-error');
    }

    if(urlImdb.value.trim() === ""){
        document.getElementById("error_url_imdb_edit").innerText = "La URL de IMDB es obligatoria";
        urlImdb.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_url_imdb_edit").innerText = "";
        urlImdb.classList.remove('input-error');
    }

    if(date.value === ""){
        document.getElementById("error_date_edit").innerText = "La fecha de estreno es obligatoria";
        date.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_date_edit").innerText = "";
        date.classList.remove('input-error');
    }

    return isValid;
}
/* Campos del create
    Título new_title
    Fecha new_date
    URL new_url_imdb
    Imagen new_url_pic
    Descripción new_desc
    Géneros new_genreCheckboxes
 */
function validateCreate(){
    let isValid = true;

    console.log("JEJE");

    const titulo = document.getElementById("new_title")
    const fecha = document.getElementById("new_date");
    const url = document.getElementById("new_url_imdb");

    const genreCheckboxes = document.querySelectorAll('#new_genreCheckboxes input[type="checkbox"]:checked');

    if(titulo.value.trim() === ""){
        document.getElementById("error_title_add").innerText = "El título es obligatorio";
        titulo.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_title_add").innerText = "";
        titulo.classList.remove('input-error');
    }

    if(fecha.value === ""){
        document.getElementById("error_date_add").innerText = "La fecha de estreno es obligatoria";
        fecha.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_date_add").innerText = "";
        fecha.classList.remove('input-error');
    }

    if(url.value.trim() === ""){
        document.getElementById("error_url_imdb_add").innerText = "La URL de IMDB es obligatoria";
        url.classList.add('input-error');
        isValid = false;
    }else{
        document.getElementById("error_url_imdb_add").innerText = "";
        url.classList.remove('input-error');
    }
        
    if (genreCheckboxes.length === 0) {
        document.getElementById('new_genreCheckboxes').classList.add('input-error');
        isValid = false;
    } else {
        document.getElementById('new_genreCheckboxes').classList.remove('input-error');
    }

    return isValid;

}


let currentPage = 1;
const rowsPerPage = 10;
let userRows;

function initializePagination() {
    userRows = document.getElementsByClassName('user-row');
    showPage(currentPage);
    updatePageInfo();
}

function showPage(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    
    // Ocultar todas las filas
    Array.from(userRows).forEach((row, index) => {
        if (index >= start && index < end) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Actualizar estado de los botones
    document.getElementById('prevButton').disabled = page === 1;
    document.getElementById('nextButton').disabled = end >= userRows.length;
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
        updatePageInfo();
    }
}

function nextPage() {
    const totalPages = Math.ceil(userRows.length / rowsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
        updatePageInfo();
    }
}

function updatePageInfo() {
    const totalPages = Math.ceil(userRows.length / rowsPerPage);
    document.getElementById('pageInfo').textContent = `Página ${currentPage} de ${totalPages}`;
}

// Inicializar la paginación cuando se muestre la pestaña de usuarios
function showTab(tabName) {
    // Ocultar todas las pestañas
    const tabs = document.querySelectorAll('.tab_content');
    tabs.forEach(tab => {
        tab.style.display = 'none';
    });

    // Eliminar la clase 'active' de todos los botones
    const buttons = document.querySelectorAll('.tab_button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });

    // Añadir la clase 'active' al botón correspondiente
    const activeButton = document.getElementById(`${tabName}Tab`);
    activeButton.classList.add('active');

    // Mostrar el contenido de la pestaña seleccionada
    document.getElementById(tabName).style.display = 'flex';

    // Actualizar la posición y tamaño de la línea activa
    const activeLine = document.getElementById('activeLine');
    activeLine.style.width = `${activeButton.offsetWidth}px`;
    activeLine.style.left = `${activeButton.offsetLeft}px`;
    
    if (tabName === 'users') {
        initializePagination();
    }
}


function openDestroyUserModal(id)
    {
        document.getElementById("destroyUserModal").style.display = "flex"
        // Desactivar el scroll en la página principal
        // Acción para confirmar la eliminación
        document.getElementById("confirmDestroyButton").addEventListener("click", function() {
            // Aquí haces la petición para eliminar la película
            window.location.href = `index.php?controlador=admin&action=delete_user&id=${id}`;
        closeDestroyModal();  // Cierra el modal después de la eliminación
        });
    }


function closeDestroyUserModal()
{
    document.getElementById("destroyUserModal").style.display = "none";
}