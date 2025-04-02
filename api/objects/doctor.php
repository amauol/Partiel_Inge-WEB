<?php
class Doctor{
 
    // database connection and table name
    private $conn;
    private $table_name = "doctors";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $gender;
    public $specialist;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all doctors
    function read(){
    
        // select all query
        $query = "SELECT
                    `id`, `name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // get single doctor data
    function read_single(){
    
        // select all query
        $query = "SELECT
                    `id`, `name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`
                FROM
                    " . $this->table_name . " 
                WHERE
                    id= '".$this->id."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create doctor
    function create(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`)
                  VALUES
                        ('".$this->name."', '".$this->email."', '".$this->password."', '".$this->phone."', '".$this->gender."', '".$this->specialist."', '".$this->created."')";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // update doctor 
    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name='".$this->name."', email='".$this->email."', password='".$this->password."', phone='".$this->phone."', gender='".$this->gender."', specialist='".$this->specialist."'
                WHERE
                    id='".$this->id."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // delete doctor
    function delete(){
        
        // query to insert record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                email='".$this->email."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function login($email, $password){
        // Requête pour obtenir le mot de passe hashé et l'ID
        $query = "SELECT id, name, password FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        
        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
        
        // Liaison de l'email
        $stmt->bindParam(':email', $email);
        
        // Exécution de la requête
        $stmt->execute();
        
        // Si un utilisateur est trouvé
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérification du mot de passe avec bcrypt via password_verify()
            if (password_verify($password, $row['password'])) {
                // Retourner les informations de l'utilisateur si le mot de passe est correct
                return ["success" => true, "user" => ["id" => $row['id'], "name" => $row['name']]];
            }
        }
        
        // Si l'utilisateur n'est pas trouvé ou si le mot de passe est incorrect
        return ["success" => false, "message" => "Email ou mot de passe incorrect."];
    }
}