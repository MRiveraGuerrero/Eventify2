window.onload = init 

function init(){
    document.getElementById("changePhotoBtn")?.addEventListener("click", function() {
        document.getElementById("photoInput").click();
    });

    document.getElementById("photoInput")?.addEventListener("change", async function(e) {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('profile_photo', file);
            formData.append('token', document.querySelector('input[name="token"]').value);

            try {
                const response = await fetch('/update_profile_photo.php', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    document.getElementById("profileImage").src = data.photo_url;
                    alert('Foto de perfil actualizada con éxito');
                } else {
                    alert('Error al actualizar la foto de perfil');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al actualizar la foto de perfil');
            }
        }
    });
    document.getElementById("botonPerfil").addEventListener("click", () => {
        event.preventDefault();
        var aceptado = true
        var nombre = document.getElementsByName("nombre")[0]?.value
        var telefono = document.getElementsByName("telefono")[0]?.value
        var email = document.getElementsByName("email")[0]?.value
        var nacimiento = document.getElementsByName("nacimiento")[0]?.value
        var usuario = document.getElementsByName("usuario")[0]?.value
        var passwd = document.getElementsByName("passwd")[0]?.value

        aceptado = aceptado && comprobarNombre(nombre)

        aceptado = aceptado && comprobarTelefono(telefono)

        aceptado = aceptado && comprobarEmail(email)

        aceptado = aceptado && comprobarNacimiento(nacimiento)
        

        aceptado = aceptado && comprobarUsuario(usuario)

        aceptado = aceptado && comprobarPasswd(passwd)

        if(aceptado){
            var form = document.getElementById("form-registro")
            form.submit()
        }
    })
    document.getElementById("botonEliminar").addEventListener("click", () => {
        event.preventDefault();
        var eliminar = document.getElementById("eliminar")
        eliminar.value = true
        var form = document.getElementById("form-registro")
        form.submit()
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


