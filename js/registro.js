const url = '../api/api_registro1.php';
var data = [];

function readAllUsers(){
    axios({
        method:'GET',
        url:url,
        responseType:'json'
        }).then(res =>{
            console.log(res.data);
            this.data = res.data.data;
            console.log(res.data.status);
            if((res.data.status=="error"))
                window.location.href = "401.html";
            else
                llenarTabla(data);            
        }).catch(error=>{
                console.error(error);
        });
}

function llenarTabla(data)
{
    document.querySelector('#table-user tbody').innerHTML = '';
    for(let i=0;i<data.length;i++){
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

function deleteUser(id_del){
    let user = { 
        id : id_del };
    axios({
        method:'DELETE',
        url:url,
        responseType:'json',
        data: user
        }).then(res =>{
            console.log(res.data);
            readAllUsers();        
        }).catch(error=>{
            console.error(error);
        });        
}

function createUser(){
let user = { 
    nombre : document.getElementById('nombre').value,
    identificacion : document.getElementById('identificacion').value,
    fecha : document.getElementById('fecha').value,
    tipo_identificacion : document.getElementById('tipo_identificacion').value,
    motivo: document.getElementById('motivo').value
    };

    axios({
        method:'POST',
        url:url,
        responseType:'json',
        data: user
        }).then(res =>{
            console.log(res.data);
            if(res.data.message=='Duplicate data')
                alert('Dato duplicado.');
            else
                readAllUsers();        
        }).catch(error=>{
            console.error(error);
        });
}

function updateUser(id_update){
    let name_update = document.getElementById('nombre').value;
    if ( name_update!= "")
    {
    
    let user = { 
        id : id_update,
        nombre : name_update,
        identificacion : document.getElementById('identificacion').value,
        fecha : document.getElementById('fecha').value,
        tipo_identificacion : document.getElementById('tipo_identificacion').value,
        motivo: document.getElementById('motivo').value
        };

    axios({
        method:'PUT',
        url:url,
        responseType:'json',
        data: user
        }).then(res =>{
            console.log(res.data);
            if(res.data.status==='error')
            alert('Dato duplicado.');
        else
            readAllUsers();         
        }).catch(error=>{
            console.error(error);
        });
    }
    else
    alert("Debe colocar un nombre")

}

function readUserById(id){
    axios({
        method:'GET',
        url:url + '?id='+ id,
        responseType:'json'
        }).then(res =>{
            console.log(res.data);
            document.getElementById('nombre').value = res.data.data[0].user_nombre;
            document.getElementById('identificacion').value = res.data.data[0].user_identificacion;
            document.getElementById('fecha').value = res.data.data[0].user_fecha;
            document.getElementById('tipo_identificacion').value = res.data.data[0].user_tipo_identificacion;
            document.getElementById('motivo').value = res.data.data[0].user_motivo;
        }).catch(error=>{
            console.error(error);
        });
}