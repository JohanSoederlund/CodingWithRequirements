<?php

class Router {

    private $session;
    private $user;
    
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $userView;
    private $layoutView;
   
    /**
    * Constructor initiates this object with all Views, Session and User model
    */
    public function __construct() {
        $this->session = new Session();
        $this->user = new User($this->session);
        $this->loginView = new LoginView($this->session);
        $this->registerView = new RegisterView($this->session);
        $this->userView = new UserView($this->session, $this->user);
        $this->dateTimeView = new DateTimeView();
        $this->layoutView = new LayoutView($this->loginView, $this->registerView, $this->dateTimeView, $this->userView, $this->session);
        $this->route();
    }

    /**
    * Route Controller, directs the user after useraction recieved 
    */
    private function route() {
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

    /**
    * Login action responsibility
    */
    private function routeLogIn() {
        if ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
        } elseif ($this->user->tryLogin($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), $this->loginView->isHiddenSet()) ){
            $this->layoutView->renderLogOut();
        } else {
            $this->layoutView->renderLogIn();
        }
    }

    /**
    * Logout action responsibility
    */
    private function routeLogOut() {
        if ($this->session->getLoggedIn()){
            $this->user->deleteToVisibleDB();
            $this->session->terminate();
        } 
        $this->layoutView->renderLogin();
    }

    /**
    * Register action responsibility
    */
    private function routeRegister(){
        if ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
        } elseif ($this->user->tryRegister($this->registerView->getRequestRegisterUserName(), $this->registerView->getRequestRegisterPassword(), 
                        $this->registerView->getRequestRepeatPassword())) {
            $this->layoutView->renderLogIn();
        } else {
            $this->layoutView->renderRegister();
        }
    }

    /**
    * Checks query-string in Server variable, maping to client address-field location
    */
    private function isQueryStringRegister() : bool {
        return (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register");
    }

}


