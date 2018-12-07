<?php
/**
 * Created by PhpStorm.
 * User: Hans
 * Date: 01/12/2018
 * Time: 02:18
 */

//include_once '/model/MessagesService.php';
include($_SERVER["DOCUMENT_ROOT"] . "/Werkpakket_2/model/MessagesService.php");

class MessagesController
{
    private $messagesService = NULL;

    public function __construct()
    {
        $this->messagesService = new MessagesService();
    }

/*    public function redirect($location){
        header('Location: '.$location);
    }*/

    public function listMessages(){
        $messages = $this->messagesService->getAllMessages();
        //print($messages);
        include($_SERVER["DOCUMENT_ROOT"] . "/Werkpakket_2/view/MessagesView.php");
    }

    public function findMessageByID($id){
        $message = $this->messagesService->getMessage($id);
        //print($message);
        include($_SERVER["DOCUMENT_ROOT"] . "/Werkpakket_2/view/MessageByIDView.php");
    }

    public function saveMessage($inhoud){
        $result = NULL;

        //if(!empty($jsonObject['inhoud']) ){
        if (!empty($inhoud))
            // set product property values
            //$inhoud = $jsonObject['inhoud'];

            // create the message
            if($this->messagesService->createNewMessage($inhoud)){

                // set response code - 201 created
                http_response_code(201);

                // tell the user
                $result = json_encode(array("Opmerking" => "Bericht werd aangemaakt."));
            }

            // if unable to create the message, tell the user
            else{

                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                $result = json_encode(array("Opmerking" => "Het bericht kan niet aangemaakt worden."));
            }

            include($_SERVER["DOCUMENT_ROOT"] . "/Werkpakket_2/view/MessageAddView.php");
    }
}

