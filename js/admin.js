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