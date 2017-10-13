<?php

session_start();

//INCLUDE THE FILES NEEDED...
require_once('controller/Router.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS


try { 
    $router = new Router();
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}




