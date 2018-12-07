<?php
/**
 * Created by PhpStorm.
 * User: Hans
 * Date: 25/11/2018
 * Time: 18:42
 */

class Database
{
    //database credentials
    private $host = "localhost";
    private $db_name = "api_db";
    private $username = "root";
    private $password = "root";
    public $conn;

    //get connection with database
    public function getConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    public function closeConnection(){
        $this->conn = null;
    }

}