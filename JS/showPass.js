function showPass(){
    const campoPass = document.getElementById("contrasena");
    campTemp = campoPass.getAttribute("type");
    if(campTemp === "password") campoPass.setAttribute("type","text");
    else if (campTemp === "text") campoPass.setAttribute("type","password");
}