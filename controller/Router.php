<?php

require_once('view/FormTemplates.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');


class Router {
    
    private $formTemplates;
    private $dateTimeView;
    private $layoutView;
    private $user;
    private $session;
   
    public function __construct() {
        $this->session = new Session();
        $this->formTemplates = new FormTemplates();
        $this->dateTimeView = new DateTimeView();
        $this->layoutView = new LayoutView($this->formTemplates, $this->dateTimeView, $this->session);
        $this->route();
    }

    private function route() {
        /*
        if (isset($_GET)) {
            if ($this->session->getLoggedIn()) {
                $this->layoutView->renderLogOut(); 
            } elseif() {
                $this->layoutView->renderLogOut(); 
            }

        }*/

        if ($this->formTemplates->loginAttempted() && !$this->session->getLoggedIn()) {
            
            $this->user = new User($this->formTemplates->getRequestUserName(), $this->formTemplates->getRequestPassword(), "", false, $this->formTemplates->keepLoggedIn());
            $this->session->setMessage($this->user->getMessage());
            $this->session->setUser($this->formTemplates->getRequestUserName(), $this->formTemplates->getRequestPassword(), $this->formTemplates->keepLoggedIn(), $this->user->getInvalidCredentials());
            //header('Location: /index.php');
            if ($this->session->getLoggedIn()) {
                $this->layoutView->renderLogOut(); 
            } else {
                throw new Exception("INVALID login? why?");
            }
        } elseif ($this->formTemplates->logoutAttempted() && $this->session->getLoggedIn()) {
            $this->session->terminate();
            //header('Location: /index.php');
            $this->layoutView->renderLogIn(); 
        } elseif ($this->formTemplates->registerAttempted()) {
            $this->user = new User($this->formTemplates->getRequestRegisterUserName(), $this->formTemplates->getRequestRegisterPassword(), $this->formTemplates->getRequestRepeatPassword(), true, false);
            $this->session->setMessage($this->user->getMessage());
            if (!$this->user->getInvalidCredentials()) {
                //header('Location: /index.php');
                $this->layoutView->renderLogIn();
            } else {
                //header("Location: /index.php?". $_SERVER['?register']);
                $this->layoutView->renderRegister();
            }
        } elseif ($this->session->isLoggedInCookieValid() && !$this->session->getLoggedIn()) {
            $this->session->logInWithCookies();
            if ($this->session->getLoggedIn()){
                //header('Location: /index.php');
                $this->layoutView->renderLogOut();
            } else {
                throw new Exception("BAD COOKIES");
            }
        } elseif (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == "register"){
            $this->layoutView->renderRegister();
        } else {
            //header('Location: /index.php');
            $this->layoutView->renderLogin();
        }
        
        
    }
/*
    private function getQueryString() : string {
        $this->queryStrings[0];
        //explode("=", urldecode($_SERVER['QUERY_STRING']))
        if (isset($_SERVER['QUERY_STRING']) ){
            //var_dump($_SERVER['QUERY_STRING']);
            foreach($this->queryStrings as $qs){
                if ($qs == $_SERVER['QUERY_STRING']) {
                    return $qs;
                }
            }
        } 
        return $this->queryStrings[0];
    }*/

}
