<?php
/**
 * Created by PhpStorm.
 * User: Hans
 * Date: 29/11/2018
 * Time: 23:13
 */

//include dirname('MessageGateway.php');
include_once 'MessageGateway.php';
include_once 'Database.php';


class MessagesService
{
    private $messagesGateway = NULL;
    private $database = NULL;
    private $db = NULL;

    public function __construct()
    {
        $this->database = new Database();
        $this->messagesGateway = new MessageGateway();
    }

    private function openDB(){
        $this->db = $this->database->getConnection();
    }

    private function closeDB(){
        $this->database->closeConnection();
    }

    public function getAllMessages(){
        try{
            $this->openDB();
            //receiving json object from MessageGateway
            $res = $this->messagesGateway->selectAll($this->db);
            $this->closeDB();
            //returning json object to controller
            return $res;
        } catch (Exception $e) {
            $this->closeDB();
            return $e;
        }
    }

    public function getMessage($id){
        try{
            $this->openDB();
            $res = $this->messagesGateway->selectById($id,$this->db);
            $this->closeDB();
            return $res;
        } catch (Exception $e) {
            $this->closeDB();
            return $e;
        }
    }

    private function validateMessageParams($inhoud){
        $error = '';
        if (!isset($inhoud) || empty($inhoud)){
            $error = 'Het bericht moet een inhoud hebben!';
        }
        if (empty($error)){
            return;
        }
        throw new ValidationException($error);
    }

    public function createNewMessage($inhoud){
        try {
            $this->openDB();
            //$this->validateMessageParams($inhoud);
            $res = $this->messagesGateway->insert($inhoud, $this->db);
            $this->closeDB();
            return true;
        } catch (Exception $e){
            $this->closeDB();
            return $e;
        }
    }

}