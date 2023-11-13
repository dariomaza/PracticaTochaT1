function validarFormulario() {
    const campos = document.querySelectorAll('input');

    for (let i = 0; i < campos.length; i++) {
        if(campos[i].value === "") {
            alert("No se admiten campos vacios");
            return false;
        }
    }
    return true;
}