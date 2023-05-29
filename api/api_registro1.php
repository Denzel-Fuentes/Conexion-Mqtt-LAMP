<?php 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Content-Type: application/json; charset=UTF-8");
    include_once '../class/class_registro.php';
    
    $user = new registro_class();
    function validarDatos($nombre, $identificacion, $fecha, $tipo_identificacion, $motivo) {
        // Validar que no haya campos vacíos
        if (empty($nombre) || empty($identificacion) || empty($fecha) || empty($tipo_identificacion) || empty($motivo)) {
            return 'Por favor, complete todos los campos.';
        }
        // Validar longitud máxima de los campos
        elseif (strlen($nombre) > 50 || strlen($identificacion) > 50 || strlen($motivo) > 50) {
            return 'La longitud máxima permitida para los campos es de 50 caracteres.';
        }
        // Validar tipo de identificación
        elseif (!in_array($tipo_identificacion, ['Carnet de Identidad', 'Carnet Univalle', 'Licencia de Conducir', 'Pasaporte'])) {
            return 'Tipo de Identificación inválido. Ingrese cualquier de las siguientes opciones {Carnet de Identidad, Carnet Univalle,Licencia de Conducir, Pasaporte}' ;
        }
        
        return ''; // Devuelve una cadena vacía si todas las validaciones son exitosas
    }
    
    switch($_SERVER['REQUEST_METHOD']){
        

        case 'GET': //get a single o list
            
            
            if(isset($_GET['id'])){
                $getId = $user->getregistro($_GET['id'],null);
                if(is_array($getId))
                {
                    $result["data"] = $getId;
                    $result["status"] = 'success';
                }
                else
                {
                    $result["status"] = "error";
                    $result["message"] = "Unable to communicate with database";                       
                }
            }
            else
            
            if(isset($_GET['identificacion'])){
                    $getId = $user->getregistro(null,$_GET['identificacion']);
                    if(is_array($getId))
                    {
                        $result["data"] = $getId;
                        $result["status"] = 'success';
                    }
                    else
                    {
                        $result["status"] = "error";
                        $result["message"] = "Unable to communicate with database";                       
                    }
                }
            else{
                $getAll = $user->getAllregistro();
                if(is_array($getAll))
                {
                    $result["data"] = $getAll;
                    $result["status"] = 'success';
                }
                else
                {
                    $result["status"] = "error";
                    $result["message"] = "Unable to communicate with database";                       
                } 
            }
        break;        

        case 'POST': //create
            $_POST = json_decode(file_get_contents('php://input'), true);
            $nombre = $_POST['nombre'];
            $identificacion = $_POST['identificacion'];
            $fecha = $_POST['fecha'];
            $tipo_identificacion = $_POST['tipo_identificacion'];
            $motivo = $_POST['motivo'];
        
            $validacion = validarDatos($nombre, $identificacion, $fecha, $tipo_identificacion, $motivo);
        
            if (!empty($validacion)) {
                $result["status"] = 'error';
                $result["message"] = $validacion;
            } elseif ($user->insertregistro($nombre, $identificacion, $fecha, $tipo_identificacion, $motivo)) {
                $result["status"] = 'success';
                $result["data"] = $user;
            } else {
                $result["status"] = 'error';
                $result["message"] = 'Datos duplicados o inválidos.';
            }
        
        break;
       
        case 'DELETE': //delete
            $_DELETE = json_decode(file_get_contents('php://input'),true);
            $deleteId = $user->deleteregistro($_DELETE['id']);
            if($deleteId)
            {
                $result["data"] = null;
                $result["status"] = 'success';    
            }
            else
            {
                $result["status"] = "error";
                $result["message"] = "Unable to communicate with database";                       
            } 
        break;
        
        case 'PUT': //update
            $_PUT = json_decode(file_get_contents('php://input'), true);
            $id = $_PUT['id'];
            $nombre = $_PUT['nombre'];
            $identificacion = $_PUT['identificacion'];
            $fecha = $_PUT['fecha'];
            $tipo_identificacion = $_PUT['tipo_identificacion'];
            $motivo = $_PUT['motivo'];
        
            $validacion = validarDatos($nombre, $identificacion, $fecha, $tipo_identificacion, $motivo);
        
            if (!empty($validacion)) {
                $result["status"] = 'error';
                $result["message"] = $validacion;
            } elseif ($user->updateregistro($id, $nombre, $identificacion, $fecha, $tipo_identificacion, $motivo)) {
                $result["status"] = 'success';
                $result["data"] = $user;
            } else {
                $result["status"] = 'error';
                $result["message"] = 'No se pudo realizar la actualización en la base de datos o datos duplicados.';
            }
        
        break;  
        
        default:{
            $result["status"] = "error";
            $result["message"] = "Unknown request";
        }
    }
    echo json_encode($result);
