<?php

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/StoreUser.php');

class Router {

    /*private $v = new LoginView();
    private $dtv = new DateTimeView();
    private $lv = new LayoutView();
    private $storeUser = new StoreUser();*/

   
    function __construct() {
        $this->render();
    }

    private function render() {
        $v = new LoginView();
        $dtv = new DateTimeView();
        $lv = new LayoutView();
        
        $message = "";
        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        } 
        if($_SESSION['loggedIn'] === false) {

            if (isset($_REQUEST["LoginView::Login"])) {

                if (isset($_REQUEST["LoginView::UserName"]) && $_REQUEST["LoginView::UserName"] !== "") {

                    if (isset($_REQUEST["LoginView::Password"]) && $_REQUEST["LoginView::Password"] !== "") {

                        if($this->login()) {
                            $message = "Welcome";
                        } else {
                            $message = "Wrong name or password";
                        }

                    } else {
                        $message = "Password is missing";
                    }

                } else {
                    $message = "Username is missing";
                }
                
            }

        } else {

            if (isset($_REQUEST["LoginView::Logout"])) {
                $_SESSION['loggedIn'] = false;
                $message = "Bye bye!";
            }
            
        }
        
        $lv->render($message, $_SESSION['loggedIn'], $v, $dtv); 
        
        var_dump($_SESSION);
        var_dump($_REQUEST);
    }
    

    private function login() {
        $storeUser = new StoreUser();
        if ($_REQUEST["LoginView::UserName"] === $storeUser->getUserName() 
        && $_REQUEST["LoginView::Password"] === $storeUser->getPassword()) {
            $_SESSION['loggedIn'] = true;
            return true;
        }
        return false;
    }

}

