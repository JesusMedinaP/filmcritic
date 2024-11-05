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