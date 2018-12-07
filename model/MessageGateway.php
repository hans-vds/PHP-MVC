<?php

//vereiste headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'Message.php';

class MessageGateway
{
    private $conn = NULL;
    private $table_name = "messages";
    private $message = NULL;
    private $inhoud = NULL;

    // Select all messages
    public function selectAll($db){
        $this->conn = $db;
        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        //return $stmt;

        $num = $stmt->rowCount();

        // check if more than 0 record found
        if($num>0){

            // Messages array
            $messages_arr=array();
            $messages_arr["records"]=array();

            // retrieve table contents
            // fetch() is faster than fetchAll()
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row -> $row['name'] will be $name
                extract($row);

                $message_item=array(
                    "id" => $row['id'],
                    "inhoud" => $row['inhoud']
                );

                array_push($messages_arr["records"], $message_item);
            }

            // set response code - 200 OK
            http_response_code(200);

            // show messages data in json format
            return json_encode($messages_arr);
        } else{

            // set response code - 404 Not found
            http_response_code(404);

            // tell the user no messages found
            return json_encode(
                array("message" => "No products found.")
            );
        }
    }

    // Select message by ID
    public function selectById($id, $db){
        $this->conn = $db;
        $this->message = new Message;
        // query to read single record
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        //var_dump($row);

        // set values to object properties
        $this->message->setId($row['id']);
        $this->message->setInhoud($row['inhoud']);

        if($row != NULL){
            // create array
            $message_arr = array(
                "id" =>  $this->message->getId(),
                "inhoud" => $this->message->getInhoud()
            );

            // set response code - 200 OK
            http_response_code(200);

            // make it json format
            return json_encode($message_arr);
        }

        else{
            // set response code - 404 Not found
            http_response_code(404);

            // tell the user product does not exist
            return json_encode(array("message" => "Product does not exist."));
        }

    }

    public function insert($inhoud, $db){
        $this->inhoud = $inhoud;
        $this->conn = $db;
        // query to insert record
        $query = "INSERT INTO "
            . $this->table_name . "
            SET
                inhoud=:inhoud";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->inhoud=htmlspecialchars(strip_tags($this->inhoud));

        // bind values
        $stmt->bindParam(":inhoud", $this->inhoud);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;
    }
}