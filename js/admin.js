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