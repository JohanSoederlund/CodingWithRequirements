<?php


class Session {

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        if ($this->isSessionLost()) {
            $this->createSessionFromCookie();
        }
        
    }

    public function getLoggedIn() : bool {
        if(!isset($_SESSION['loggedIn'])){
            $_SESSION['loggedIn'] = false;
        }
        return $_SESSION['loggedIn'];
    }

    public function getUserName() : string{
        if(!isset($_SESSION['UserName'])){
            $_SESSION['UserName'] = "";
        }
        return $_SESSION['UserName'];
    }

    public function getMessage() : string{
        if (!isset($_SESSION['message'])){
            $_SESSION['message'] = "";
        }
        return $_SESSION['message'];
    }

    public function isLoggedInCookieValid(){
        if (isset($_COOKIE["loggedin"])) {
            return $_COOKIE["loggedin"];
        }
        return false;
    }

    private function isSessionLost() : bool {
        if (!isset($_SESSION['loggedIn']) || !isset($_SESSION['UserName'])) {
            return true;
        } 
        return false;
    }

    private function createSessionFromCookie(){
        if (isset($_COOKIE["loggedin"]) && isset($_COOKIE["password"]) && isset($_COOKIE["username"])) {    
            $this->setUser($_COOKIE["username"], $_COOKIE["password"], false, false);
            $this->setMessage("Welcome back with cookie");
        } else {
            //Create empty user?
        }
    }

    public function setMessage(string $message){
        $_SESSION['message'] = $message;
    }

    public function setUser(string $userName, string $password, bool $keepLoggedIn, bool $inValid){
        $_SESSION['UserName'] = $userName;
        if (!$inValid) {
            $this->password = $password;
            $this->keepLoggedIn = $keepLoggedIn;
            $_SESSION['loggedIn'] = true;
            if ($keepLoggedIn) {
                setcookie("username", $userName, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                setcookie("loggedin", $keepLoggedIn, time() + (86400 * 30), "/");
            }
        }
        else {
            $_SESSION['loggedIn'] = false;
        }
    }

    public function terminateSession(){
        $this->setMessage("Bye bye!");
        $this->setUser("", "", false, true);

        setcookie("username", "", time() - 3600, "/");
        setcookie("password", "", time() - 3600, "/");
        setcookie("loggedin", false, time() - 3600, "/");
    }













/*
    public function getLoggedIn() : bool {
        return $_SESSION['loggedIn'];
    }

    public function getUserName() : string{
        return $_SESSION['UserName'];
    }

    public function getMessage() : string{
        return $_SESSION['message'];
    }*/

}