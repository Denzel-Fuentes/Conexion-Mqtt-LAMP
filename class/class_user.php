<?php
    //print_r($stmt->errorInfo());
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include_once 'db.php';
    class user_class extends Database {  
        //name of the table
        private $table_name = "user";
        //name of the table's columns
        //public $id;
        //public $name;
        //public $last_name;
        //public $email;
        //public $password;

        //creation of the connection
        public function __construct(){    
            $this->conn = $this->getConnection();
        }

        //get all users
        public function getAllUsers()
        {
            $stmt = $this->conn->prepare("
            SELECT
            `user`.`id` as 'user_id', 
            `user`.`name` as 'user_name',
            `user`.`lastname` as 'user_last_name',
            `user`.`email` as 'user_email'
            FROM `user`
            ORDER by `user`.`id` ASC
            ");
            if ($stmt->execute())
                {
                    $result = array();
                    if($stmt->rowCount() > 0){
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $item = array(
                                        'user_id' => $row['user_id'],
                                        'user_name' => $row['user_name'],
                                        'user_last_name' => $row['user_last_name'],
                                        'user_email' => $row['user_email']
                                         );
                            array_push($result , $item);
                        }
                    }
                    return $result;
                }
            else
                return false;        
        }

        //get a single user
        public function getUser($user_id,$email)
        {
            $where_clause = '';
            if(isset($user_id))
                $where_clause = " WHERE `user`.`id`=  $user_id";

            if(isset($email))
                $where_clause = " WHERE `user`.`email` =  '$email'";

            $stmt = $this->conn->prepare("
            SELECT
            `user`.`id` as 'user_id', 
            `user`.`name` as 'user_name',
            `user`.`lastname` as 'user_last_name',
            `user`.`email` as 'user_email'
            FROM `user`
            $where_clause
            ORDER by `user`.`id` ASC
            ");
            if ($stmt->execute())
                //return $stmt;
                {
                    $result = array();
                    if($stmt->rowCount() > 0){
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $item = array(
                                        'user_id' => $row['user_id'],
                                        'user_name' => $row['user_name'],
                                        'user_last_name' => $row['user_last_name'],
                                        'user_email' => $row['user_email']
                                         );
                            array_push($result , $item);
                        }
                    }
                    return $result;
                }
            else
                return false;        
        }
        
        //insert user
        public function insertUser( $name, $last_name, $email, $password){                  
            $stmt= $this->conn->prepare("INSERT INTO `user` (`id`, `name`, `lastname`, `email`, `password`) 
                                         VALUES (NULL, :name, :last_name, :email, :password);") ;
            $stmt->bindValue('name', $name);
            $stmt->bindValue('last_name', $last_name);
            $stmt->bindValue('email', $email);
            $stmt->bindValue('password', $password);
            
            if ($stmt->execute())
                return true;
            else
                return false;
             
 
        }
        
        //update user
        public function updateUser($id, $name, $last_name, $email,  $password){ 
            $stmt = $this->conn->prepare("SELECT id FROM `user` WHERE `user`.`id` = :id;");
            $stmt->execute(array(':id' => $id));
            if ($stmt->rowCount()) {
                $stmt = $this->conn->prepare("
                UPDATE `user` SET 
                `name` = :name, 
                `lastname` = :last_name, 
                `email` = :email, 
                `password` = :password 
                WHERE `user`.`id` = :id;
                ");

                $stmt->bindValue('id', $id);
                $stmt->bindValue('name', $name);
                $stmt->bindValue('last_name', $last_name);
                $stmt->bindValue('email', $email);
                $stmt->bindValue('password', $password);
               
                if ($stmt->execute())
                    return true;
                else
                    return false;
                }
            else
                return false;
                  
        }

        //update user
        public function deleteUser($id){
            $stmt = $this->conn->prepare("DELETE FROM `user` WHERE `user`.`id` = :id");
            if ($stmt->execute(array('id' => $id)))
                return true;
            else
                return false;         
        }
      }
?>
