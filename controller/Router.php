<?php

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');
require_once('model/StoreUser.php');

class Router {
    //TODO
    //private static logedIn = 'Router::LogedIn'; //or true/false???
    //logedIn == true, is actually a by-product of session-timeleft > 0;
    private $loginView;
    private $dateTimeView;
    private $layoutView;
    private $storeUser;
    private $User;
    private $session;

   
    function __construct() {
        $this->render();
    }

    private function render() {
        $loginView = new LoginView();
        $dateTimeView = new DateTimeView();
        $layoutView = new LayoutView();
        $storeUser = new StoreUser();
        

        
        $message = "";
        //TODO: change hardcoded, $loginView->getLogin ... 
        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        } 

        /*
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
            
        }*/

        if ($loginView->loginAttempted()) {
            $user = new User($loginView->getRequestUserName(), $loginView->getRequestPassword());
            if ($user->invalidCredentials()) {
                $message = $user->getMeassgae();
            } else {
                //change to param USER
                $session = new Session($loginView->getRequestUserName(), $loginView->getRequestPassword(), $loginView->keepLoggedIn());
            }
            
        } elseif ($loginView->logoutAttempted()) {
            //TODO: terminate user-part of session
            $session->terminate();
        } /*elseif ($registerView->registerAttempted()) {
            //todo
        }*/
        
        $layoutView->render($message, $_SESSION['loggedIn'], $loginView, $dateTimeView); 
        
        
        var_dump($_SESSION);
        var_dump($_REQUEST);
        
    }
    

    private function login() {
        //get $loginView->UserName
        if ($_REQUEST["LoginView::UserName"] === $storeUser->getUserName() 
        && $_REQUEST["LoginView::Password"] === $storeUser->getPassword()) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['UserName'] = true;


            //LoginView::Login=login&LoginView::UserName=Admin&LoginView::Password=
            return true;
        }
        return false;
    }
    private function register() {

    }

}

