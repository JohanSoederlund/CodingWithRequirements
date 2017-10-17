<?php

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');


class Router {
    
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;
    private $user;
    private $session;
   
    public function __construct() {
        $this->session = new Session();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->dateTimeView = new DateTimeView();
        $this->layoutView = new LayoutView($this->loginView, $this->registerView, $this->dateTimeView, $this->session);
        $this->route();
    }

    private function route() {
        if ($this->loginView->loginAttempted()) {
            $this->routeLogIn();
        } elseif ($this->loginView->logoutAttempted()) {
            $this->routeLogOut();
        } elseif ($this->registerView->registerAttempted()) {
            $this->routeRegister();
        } elseif ($this->session->createSessionFromCookie()) {
            $this->layoutView->renderLogOut();
        } elseif (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register") {
            $this->routeRegister();
        } else {
            //Is logged in and went to index
            $this->routeLogIn();
        } 

    }

    private function routeLogIn(){
        if ($this->session->getLoggedIn()) {
            if ($this->session->isBrowserManipulated()) {
                $this->layoutView->renderLogIn();
            }else {
                $this->session->setMessage("");
                $this->layoutView->renderLogOut();
            }
        } elseif($this->loginView->loginAttempted()) {
            $this->user = new User($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), "", false, $this->loginView->keepLoggedIn());
            $this->session->setMessage($this->user->getMessage());
            $this->session->setUser($this->loginView->getRequestUserName(), $this->loginView->getCookiePassword(), $this->loginView->keepLoggedIn(), $this->user->getInvalidCredentials());
            if (!$this->user->getInvalidCredentials()) {
                $this->layoutView->renderLogOut();
            } else {
                $this->layoutView->renderLogIn();
            }
        } else {
            //$this->session->setMessage("");
            $this->layoutView->renderLogIn();
        }
    }

    private function routeLogOut(){
        if ($this->session->getLoggedIn()){
            $this->session->terminateSession();
        } else {
            $this->session->setMessage("");
        }
        $this->layoutView->renderLogin();
    }

    private function routeRegister(){
        if ($this->session->getLoggedIn()){
            $this->routeLogIn();
        }
        elseif ($this->registerView->registerAttempted()) {
            $this->user = new User($this->registerView->getRequestRegisterUserName(), $this->registerView->getRequestRegisterPassword(), $this->registerView->getRequestRepeatPassword(), true, false);
            $this->session->setMessage($this->user->getMessage());
            if ($this->user->getInvalidCredentials()) {
                $this->layoutView->renderRegister();
            } else {
                $this->routeLogIn();
            }
        }else {
            $this->session->setMessage("");
            $this->layoutView->renderRegister();
        }
        
    }


    

}
