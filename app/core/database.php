<?php
class database{
    public $conn = null;
    public function connect(){
        $dsn = DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
        try{
            $this->conn = new PDO($dsn,DB_USER,DB_PASS);
        }
        catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function query($query,$data = array()){
        if($this->conn == null){
            $this->connect();
        }
        $stmt = $this->conn->prepare($query);
        $check = $stmt->execute($data);
        if($check){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            if(is_array($data) && !empty($data)){
                return $data;
            }
        }
        return false;
    }
}