function validateEdit()
{
    let isValid = true;

    const title = document.getElementById("title");
    const urlImdb = document.getElementById("url_imdb");

    console.log("Los datos", title, urlImdb);

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

    return false;
}