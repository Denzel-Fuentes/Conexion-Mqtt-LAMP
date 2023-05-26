<?php

include_once 'db.php';

class registro_class extends Database {

    public function __construct(){
        $this->conn = $this->getConnection();
    }

    public function getAllregistro(){
        $stmt = $this->conn->prepare("
            SELECT
            `user`.`id` as 'user_id',
            `user`.`nombre` as 'user_nombre',
            `user`.`identificacion` as 'user_identificacion',
            `user`.`fecha` as 'user_fecha',
            `user`.`tipo_identificacion` as 'user_tipo_identificacion',
            `user`.`motivo` as 'user_motivo'
            FROM `user`
            ORDER by `user`.`id` ASC
        ");
        
        if ($stmt->execute()){
            $result = array();
            
            if($stmt->rowCount() > 0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $item = array(
                        'user_id' => $row['user_id'],
                        'user_nombre' => $row['user_nombre'],
                        'user_identificacion' => $row['user_identificacion'],
                        'user_fecha' => $row['user_fecha'],
                        'user_tipo_identificacion' => $row['user_tipo_identificacion'],
                        'user_motivo' => $row['user_motivo']
                    );
                    
                    array_push($result, $item);
                }
            }
            
            return $result;
        }
        else{
            return false;
        }
    }

    public function getregistro($user_id, $identificacion){
        $where_clause = '';
        
        if(isset($user_id)){
            $where_clause = " WHERE `user`.`id` =  $user_id";
        }

        if(isset($identificacion)){
            $where_clause = " WHERE `user`.`identificacion` = '$identificacion'";
        }

        $stmt = $this->conn->prepare("
            SELECT
            `user`.`id` as 'user_id',
            `user`.`nombre` as 'user_nombre',
            `user`.`identificacion` as 'user_identificacion',
            `user`.`fecha` as 'user_fecha',
            `user`.`tipo_identificacion` as 'user_tipo_identificacion',
            `user`.`motivo` as 'user_motivo'
            FROM `user`
            $where_clause
            ORDER by `user`.`id` ASC
        ");
        
        if ($stmt->execute()){
            $result = array();
            
            if($stmt->rowCount() > 0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $item = array(
                        'user_id' => $row['user_id'],
                        'user_nombre' => $row['user_nombre'],
                        'user_identificacion' => $row['user_identificacion'],
                        'user_fecha' => $row['user_fecha'],
                        'user_tipo_identificacion' => $row['user_tipo_identificacion'],
                        'user_motivo' => $row['user_motivo']
                    );
                    
                    array_push($result, $item);
                }
            }
            
            return $result;
        }
        else{
            return false;
        }
    }

    public function insertregistro($nombre, $identificacion, $fecha, $tipo_identificacion, $motivo){
        $stmt = $this->conn->prepare("
            INSERT INTO `user` (`id`, `nombre`, `identificacion`, `fecha`, `tipo_identificacion`, `motivo`)
            VALUES (NULL, :nombre, :identificacion, :fecha, :tipo_identificacion, :motivo);
        ");
        
        $stmt->bindValue('nombre', $nombre);
        $stmt->bindValue('identificacion', $identificacion);
        $stmt->bindValue('fecha', $fecha);
        $stmt->bindValue('tipo_identificacion', $tipo_identificacion);
        $stmt->bindValue('motivo', $motivo);

        if ($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateregistro($id, $nombre, $identificacion, $fecha, $tipo_identificacion, $motivo){
        $stmt = $this->conn->prepare("
            SELECT id FROM `user` WHERE `user`.`id` = :id;
        ");
        
        $stmt->execute(array(':id' => $id));
        
        if ($stmt->rowCount()){
            $stmt = $this->conn->prepare("
                UPDATE `user` SET 
                `nombre` = :nombre, 
                `identificacion` = :identificacion, 
                `fecha` = :fecha, 
                `tipo_identificacion` = :tipo_identificacion, 
                `motivo` = :motivo 
                WHERE `user`.`id` = :id;
            ");

            $stmt->bindValue('id', $id);
            $stmt->bindValue('nombre', $nombre);
            $stmt->bindValue('identificacion', $identificacion);
            $stmt->bindValue('fecha', $fecha);
            $stmt->bindValue('tipo_identificacion', $tipo_identificacion);
            $stmt->bindValue('motivo', $motivo);

            if ($stmt->execute()){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function deleteregistro($id){
        $stmt = $this->conn->prepare("
            DELETE FROM `user` WHERE `user`.`id` = :id
        ");
        
        if ($stmt->execute(array('id' => $id))){
            return true;
        }
        else{
            return false;
        }
    }
}
