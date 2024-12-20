window.onload = init

// https://stackoverflow.com/q/8935632 comprobar números
// https://stackoverflow.com/q/9862761 comprobar letras
function init() {
    var boton = document.getElementById("botonRegistro")
    boton.addEventListener("click", async (e) => {
        e.preventDefault();
        var aceptado = true;
        var nombre = document.getElementsByName("nombre")[0]?.value;
        var telefono = document.getElementsByName("telefono")[0]?.value;
        var email = document.getElementsByName("email")[0]?.value;
        var nacimiento = document.getElementsByName("nacimiento")[0]?.value;
        var usuario = document.getElementsByName("usuario")[0]?.value;
        var passwd = document.getElementsByName("passwd")[0]?.value;

        if(document.getElementById("botonIniciar").innerHTML !== "Cambiar a Crear cuenta"){
            aceptado = aceptado && comprobarNombre(nombre);
            aceptado = aceptado && comprobarTelefono(telefono);
            aceptado = aceptado && comprobarEmail(email);
            aceptado = aceptado && comprobarNacimiento(nacimiento);
            aceptado = aceptado && comprobarPasswd(passwd);
        }
        
        aceptado = aceptado && comprobarUsuario(usuario);

        if(aceptado) {
            if(document.getElementById("botonIniciar").innerHTML !== "Cambiar a Crear cuenta") {
                showTermsModal();
            } else {
                var form = document.getElementById("form-registro");
                form.submit();
            }
        }
    });

    var botonSesion = document.getElementById("botonIniciar")
    botonSesion.addEventListener("click", () => {
        event.preventDefault()

        var nombre = document.getElementById("linea-nombre")
        var telefono = document.getElementById("linea-telefono")
        var email = document.getElementById("linea-email")
        var nacimiento = document.getElementById("linea-nacimiento")
        var tipo = document.getElementsByName("tiporegistro")[0]
        var titulo = document.getElementsByClassName("form-title")[0]
        var usuario = document.getElementById("usuario-texto")
        var passwd = document.getElementById("passwd-texto")
        var desc = document.getElementsByClassName("desc")[0]
        
        if(botonSesion.innerHTML === "Cambiar a Iniciar sesión"){
            titulo.innerHTML = "Iniciar sesión"
            desc.style.display = "none"
            usuario.innerHTML = "Nombre de usuario:"
            passwd.innerHTML = "Contraseña:"
            botonSesion.innerHTML = "Cambiar a Crear cuenta"
            nombre.style.display = "none"
            telefono.style.display = "none"
            email.style.display = "none"
            nacimiento.style.display = "none"
            tipo.value = "signin" // iniciar sesión
        }else{
            titulo.innerHTML = "Registro"
            desc.style.display = "block"
            botonSesion.innerHTML = "Cambiar a Iniciar sesión"
            usuario.innerHTML = "Nombre de usuario: JonTom123"
            passwd.innerHTML = "Contraseña: asd$27"
            nombre.style.display = "table-row"
            telefono.style.display = "table-row"
            email.style.display = "table-row"
            nacimiento.style.display = "table-row"
            tipo.value = "signup" // Crear cuenta
            
        }
    })
}

function comprobarTelefono(telefono){
    // 9 números
    var aceptado = true
    for(const char of telefono){
        aceptado = aceptado && char >= '0' && char <= '9'
    }
    if(!aceptado){
        alert("El teléfono tiene que estar compuesto solo de números")
    }else{
        aceptado = telefono.length === 9
        if(!aceptado){
            alert("El teléfono tiene que tener 9 números")
        }
    }

    return aceptado
    
}

// /regex/.test(string)
// funciones creadas por chatgpt

function comprobarNombre(nombre) {
    // Solo letras y espacios
    if (/^[A-Za-z\sñÑáéíóúÁÉÍÓÚçÇ]+$/.test(nombre)) {
        return true
    } else {
        alert("Solo se admiten letras y espacios en el nombre")
        return false
    }
}

function comprobarEmail(email) {
    // Comprobar email
    if (/^[a-zA-Z0-9._-ñÑ]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/.test(email)) {
        return true
    } else {
        alert("Email no válido")
        return false
    }
}

function comprobarNacimiento(nacimiento) {
    // comprobar si encaja con alguna de las 2 yyyy-mm-dd o dd-mm-yyyy siendo números
    if (/^\d{4}-\d{2}-\d{2}$|^\d{2}-\d{2}-\d{4}$/.test(nacimiento)) {
        return true
    } else {
        alert("La fecha de nacimiento debe seguir estos formatos: yyyy-mm-dd o dd-mm-yyyy")
        return false
    }
}

function comprobarUsuario(usuario) {
    // números y letras
    if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚçÇ]+$/.test(usuario)) {
        return true
    } else {
        alert("El usuario debe incluir solo números y letras")
        return false
    }
}
  
  function comprobarPasswd(passwd){
    if(passwd.length > 0){
        return true
    }else{
        alert("El campo de la contraseña no puede estar vacío")
        return false
    }
  }

function showTermsModal() {
  const modal = document.getElementById("termsModal");
  const acceptBtn = document.getElementById("acceptTerms");
  const declineBtn = document.getElementById("declineTerms");
  const consentCheckbox = document.getElementById("termsConsent");
  const form = document.getElementById("form-registro");

  modal.style.display = "block";

  consentCheckbox.addEventListener("change", function() {
    acceptBtn.disabled = !this.checked;
  });

  acceptBtn.addEventListener("click", function() {
    modal.style.display = "none";
    form.submit();
  });

  declineBtn.addEventListener("click", function() {
    modal.style.display = "none";
  });
}


