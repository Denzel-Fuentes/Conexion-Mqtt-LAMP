const url = '../api/api_registro1.php';
var data = [];

function readAllUsers() {
    axios({
        method: 'GET',
        url: url,
        responseType: 'json'
    }).then(res => {
        console.log(res.data);
        this.data = res.data.data;
        console.log(res.data.status);
        if ((res.data.status == "error"))
            window.location.href = "401.html";
        else
            llenarTabla(data);
    }).catch(error => {
        console.error(error);
    });
}

function llenarTabla(data) {
    document.querySelector('#table-user tbody').innerHTML = '';
    for (let i = 0; i < data.length; i++) {
        document.querySelector('#table-user tbody').innerHTML +=
            `<tr>
            <td>${data[i].user_id}</td>
            <td>${data[i].user_nombre}</td>
            <td>${data[i].user_identificacion}</td>
            <td>${data[i].user_fecha}</td>
            <td>${data[i].user_tipo_identificacion}</td>
             <td>${data[i].user_motivo}</td>
            <td><button type="button" onclick="deleteUser(${data[i].user_id})">Delete</button>
            <button type="button" onclick="updateUser(${data[i].user_id})">Update</button> 
            <button type="button" onclick="readUserById(${data[i].user_id})">Read</button> </td>
        </tr>`
    }
}

function deleteUser(id_del) {
    let user = {
        id: id_del
    };
    axios({
        method: 'DELETE',
        url: url,
        responseType: 'json',
        data: user
    }).then(res => {
        console.log(res.data);
        readAllUsers();
    }).catch(error => {
        console.error(error);
    });
}

function createUser() {
    if (!validarDatos()) return
    let user = {
        nombre: document.getElementById('nombre').value,
        identificacion: document.getElementById('identificacion').value,
        fecha: document.getElementById('fecha').value,
        tipo_identificacion: document.getElementById('tipo_identificacion').value,
        motivo: document.getElementById('motivo').value
    };

    axios({
        method: 'POST',
        url: url,
        responseType: 'json',
        data: user
    }).then(res => {
        console.log(res.data);
        if (res.data.message == 'Duplicate data')
            alert('Dato duplicado.');
        else
            readAllUsers();
    }).catch(error => {
        console.error(error);
    });
}

function updateUser(id_update) {
    if (!validarDatos()) return
    let name_update = document.getElementById('nombre').value;
    if (name_update != "") {

        let user = {
            id: id_update,
            nombre: name_update,
            identificacion: document.getElementById('identificacion').value,
            fecha: document.getElementById('fecha').value,
            tipo_identificacion: document.getElementById('tipo_identificacion').value,
            motivo: document.getElementById('motivo').value
        };

        axios({
            method: 'PUT',
            url: url,
            responseType: 'json',
            data: user
        }).then(res => {
            console.log(res.data);
            if (res.data.status === 'error')
                alert('Dato duplicado.');
            else
                readAllUsers();
        }).catch(error => {
            console.error(error);
        });
    }
    else
        alert("Debe colocar un nombre")

}

function readUserById(id) {
    axios({
        method: 'GET',
        url: url + '?id=' + id,
        responseType: 'json'
    }).then(res => {
        console.log(res.data);
        document.getElementById('nombre').value = res.data.data[0].user_nombre;
        document.getElementById('identificacion').value = res.data.data[0].user_identificacion;
        document.getElementById('fecha').value = res.data.data[0].user_fecha;
        document.getElementById('tipo_identificacion').value = res.data.data[0].user_tipo_identificacion;
        document.getElementById('motivo').value = res.data.data[0].user_motivo;
    }).catch(error => {
        console.error(error);
    });
}

function validarDatos() {
    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombre").value;
    var identificacion = document.getElementById("identificacion").value;
    var fecha = document.getElementById("fecha").value;
    var tipoIdentificacion = document.getElementById("tipo_identificacion").value;
    var motivo = document.getElementById("motivo").value;

    // Validar que ningún campo esté vacío
    if (nombre === "" || identificacion === "" || fecha === "" || tipoIdentificacion === "" || motivo === "") {
        showIncompleteFieldsMessage();
        return false;
    }

    // Validar que los campos no excedan los 50 caracteres
    if (nombre.length > 50 || identificacion.length > 50 || motivo.length > 50) {
        showAlert("Los campos deben tener un máximo de 50 caracteres.");
        return false;
    }

    // Validar que el campo de Tipo de Identificación tenga un valor válido
    var allowedValues = ["Carnet de Identidad", "Carnet Univalle", "Licencia de Conducir", "Pasaporte"];
    if (!allowedValues.includes(tipoIdentificacion)) {
        showAlert("Selecciona un Tipo de Identificación válido.");
        return false;
    }
    var alertElement = document.getElementById("alertMessage");
    alertElement.innerText = '';
    alertElement.classList.remove("alert", "alert-danger")
    // Si todas las validaciones son exitosas, puedes proceder a guardar los datos o realizar otras acciones
    // Aquí puedes agregar el código para guardar los datos en una base de datos o realizar alguna otra acción
    return true;
    // Ejemplo de guardado de datos en la consola
    console.log("Nombre:", nombre);
    console.log("Identificación:", identificacion);
    console.log("Fecha:", fecha);
    console.log("Tipo de Identificación:", tipoIdentificacion);
    console.log("Motivo:", motivo);

    // Opcionalmente, puedes limpiar los campos del formulario después de guardar los datos
    /*     document.getElementById("nombre").value = "";
        document.getElementById("identificacion").value = "";
        document.getElementById("fecha").value = "";
        document.getElementById("tipo_identificacion").value = "";
        document.getElementById("motivo").value = ""; */
}

function showAlert(message) {
    // Mostrar el mensaje de alerta en un elemento HTML
    var alertElement = document.getElementById("alertMessage");
    alertElement.innerText = message;
}
function showIncompleteFieldsMessage() {
    var alertElement = document.getElementById("alertMessage");
    var incompleteFields = [];

    if (document.getElementById("nombre").value === "") {
        incompleteFields.push("Nombre");
    }
    if (document.getElementById("identificacion").value === "") {
        incompleteFields.push("Identificación");
    }
    if (document.getElementById("fecha").value === "") {
        incompleteFields.push("Fecha");
    }
    if (document.getElementById("tipo_identificacion").value === "") {
        incompleteFields.push("Tipo de Identificación");
    }
    if (document.getElementById("motivo").value === "") {
        incompleteFields.push("Motivo");
    }

    if (incompleteFields.length > 0) {
        var message = "Por favor, completa los siguientes campos: " + incompleteFields.join(", ");
        alertElement.innerText = message;
        alertElement.classList.add("alert", "alert-danger");
    }
}
// validar que el nombre solo sean letras
function validateLettersOnly(input) {
    var regex = /^[A-Za-z\s]+$/;
    if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^A-Za-z\s]/g, '');
    }
}