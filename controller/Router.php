<?php

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');


class Router {
    //TODO
    //private static logedIn = 'Router::LogedIn'; //or true/false???
    //logedIn == true, is actually a by-product of session-timeleft > 0;
    private $loginView;
    private $dateTimeView;
    private $layoutView;
    
    private $User;
    private $session;

   
    function __construct() {
        $this->render();
    }

    private function render() {
        $loginView = new LoginView();
        $dateTimeView = new DateTimeView();
        $layoutView = new LayoutView();
        $session = new Session();
        //session_set_cookie_params('o, /', 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
        $message = "";
        //TODO: change hardcoded, $loginView->getLogin ... 
        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        } 
        if (isset($_SERVER['QUERY_STRING'])) {
            if ($_SERVER['QUERY_STRING'] === "register=1") {
                $register = true;
            } else {
                $register = false;
            }
        } else {
            $register = false;
        }
         
        if ($loginView->loginAttempted() && !$_SESSION['loggedIn']) {
            $user = new User($loginView->getRequestUserName(), $loginView->getRequestPassword(), "", false, $loginView->keepLoggedIn());
            if ($user->getInvalidCredentials()) {
                $message = $user->getMessage();
                $session->setUser($loginView->getRequestUserName());
            } else {
                $message = $user->getMessage();
                $session->setLogedInUser($loginView->getRequestUserName(), $loginView->getRequestPassword(), $loginView->keepLoggedIn());
            }
            
        } elseif ($loginView->logoutAttempted() && $_SESSION['loggedIn']) {
            //TODO: terminate user-part of session
            $message = "Bye bye!";
            $session->terminate();
        } elseif ($loginView->registerAttempted()) {
            //Todo try to register
            $user = new User($loginView->getRequestRegisterUserName(), $loginView->getRequestRegisterPassword(), $loginView->getRequestRepeatPassword(), true, false);
            var_dump($user->getInvalidCredentials());
            var_dump($user->getMessage());
            if ($user->getInvalidCredentials()) {
                $message = $user->getMessage();
            } else {
                $message = $user->getMessage();
                //change to param USER
                $_SERVER['QUERY_STRING'] = "";
                $register = false;
            }
        } 
        else if ($session->isLogedInCookieValid() && !$_SESSION['loggedIn']) {
            $session->logInWithCookies();
            $message = "Welcome back with cookie";
        }
        
        $layoutView->render($message, $register, $_SESSION['loggedIn'], $loginView, $dateTimeView); 
    }
}

