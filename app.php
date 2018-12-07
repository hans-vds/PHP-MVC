<?php

//header("Content-Type: text/html");
//require 'includes/AltoRouter.php';
include dirname(__FILE__) . '/includes/AltoRouter.php';
include dirname(__FILE__) . '/controller/MessagesController.php';


/*$messagesController = new MessagesController();
//$messagesController->saveMessage("De bomen verliezen hun bladeren.");
//$messagesController->findMessageByID(2);
$messagesController->listMessages();*/
try {
    $messagesController= new MessagesController();
    $router = new AltoRouter();
    $router->setBasePath('/');

    $router->map(
        'GET',
        'messages/[i:id]',
        function ($id) use ($messagesController) {
            $messagesController->findMessageByID($id);
        }
    );
    $router->map(
        'GET',
        'messages/',
        function () use ($messagesController) {
            $messagesController->listMessages();
        }
    );
    $router->map(
        'POST',
        'messages/',
        function () use ($messagesController) {
            $requestBody = file_get_contents('php://input');
            $jsonObject=json_decode($requestBody);
            $messagesController->saveMessage($jsonObject);
        }
    );


    $match = $router->match();
    var_dump($match);
    //call_user_func_array($match['target'], $match['params']);
    if ($match && is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
        print 'error first if';
    } else {
        print 'error else';
        http_response_code(500);
    }
} catch (Exception $exception) {
    print $exception;
    http_response_code(500);
}