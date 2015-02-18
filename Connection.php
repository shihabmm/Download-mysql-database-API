<?php
class Connection{

    protected $db;
    
    public function Connection($dbname,$uname,$pwd){

    $conn = NULL;

        try{
            $conn = new PDO("mysql:host=localhost;dbname=$dbname", $uname, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo 'ERROR: ' . $e->getMessage();
                }    
            $this->db = $conn;
    }
    
    public function getConnection(){
        return $this->db;
    }
}

?>
