<?php

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');


class Router {

    private $session;
    private $user;
    
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;
   
    public function __construct() {
        $this->session = new Session();
        $this->user = new User($this->session);
        $this->loginView = new LoginView($this->session);
        $this->registerView = new RegisterView($this->session);
        $this->dateTimeView = new DateTimeView();
        $this->layoutView = new LayoutView($this->loginView, $this->registerView, $this->dateTimeView, $this->session);
        $this->route();
    }

    private function route() {
        //check typeof return value from get session in session
        if (!$this->session->getLoggedIn() && $this->loginView->isCookieValid()) {
            $this->routeLogIn();
        } elseif($this->loginView->isLoginAttempted()) {
            $this->routeLogIn();
        } elseif($this->loginView->isLogoutAttempted()) {
            $this->routeLogOut();
        } elseif($this->registerView->registerAttempted()) {
            $this->routeRegister();
        } elseif ($this->session->getLoggedIn()){
            $this->layoutView->renderLogOut();
        } elseif($this->isQueryStringRegister()) {
            $this->layoutView->renderRegister();
        } else {
            $this->layoutView->renderLogIn();
        }
    }

    private function routeLogIn() {
        if ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
            //header("Location: http://localhost:8080/index.php?");
            //die();
        } elseif ($this->user->tryLogin($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword())){
            $this->layoutView->renderLogOut();
            //header("Location: http://localhost:8080/index.php?");
            //die();
        } else {
            $this->layoutView->renderLogIn();
            //header("Location: http://localhost:8080/index.php?");
            //die();
        }
    }

    private function routeLogOut() {
        if ($this->session->getLoggedIn()){
            $this->session->terminate();
        } 
        $this->layoutView->renderLogin();
        //header("Location: http://localhost:8080/index.php?");
        //die();
    }

    private function routeRegister(){
        if ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
            //header("Location: http://localhost:8080/index.php?");
            //die();
        } elseif ($this->user->tryRegister($this->registerView->getRequestRegisterUserName(), $this->registerView->getRequestRegisterPassword(), 
                        $this->registerView->getRequestRepeatPassword())) {
            $this->layoutView->renderLogIn();
            //header("Location: http://localhost:8080/index.php?");
            //die();
        } else {
            $this->layoutView->renderRegister();
            //header("Location: http://localhost:8080/index.php?register");
            //die();
        }
    }

    private function isQueryStringRegister() : bool {
        return (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register");
    }

}


