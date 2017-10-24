<?php

/**
 * README in prject root for project description 
 *
 * PHP version 7
 *
 * @author     Original Author Daniel Toll <dntoll@github.com>
 * @author     Johan Söderlund <JohanSoederlund@github.com>
 * @copyright  https://github.com/dntoll/1dv610/blob/master/LICENSE
 * @version    V2.0
 * @link       https://github.com/JohanSoederlund/CodingWithRequirements
 * @since      File available since Release 1.1, 2017-10-04
 */

//Initializing session data immediately to avoid problems
session_start();

//INCLUDE THE FILES NEEDED...
require_once('controller/Router.php');

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/UserView.php');

require_once('model/Session.php');
require_once('model/User.php');
require_once('model/SaveUser.php');
require_once('model/SaveCookie.php');
require_once('model/Cookie.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//Instatiating routing object
try { 
    $router = new Router();
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}




