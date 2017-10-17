<?php


class Session {

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        //$this->setUser("", "", false, true);
    }

    public function getLoggedIn() : bool {
        if(!isset($_SESSION['loggedin'])){
            $_SESSION['loggedin'] = false;
        }
        return $_SESSION['loggedin'];
    }

    public function getUserName() : string{
        if(!isset($_SESSION['username'])){
            $_SESSION['username'] = "";
        }
        return $_SESSION['username'];
    }

    public function getMessage() : string{
        if (!isset($_SESSION['message'])){
            $_SESSION['message'] = "";
        }
        return $_SESSION['message'];
    }

    public function isSessionLost() : bool {
        if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username']) || !$_SESSION['loggedin']) {
            return true;
        } 
        return false;
    }

    public function createSessionFromCookie() : bool {
        if ($this->isSessionLost()) {
            if (isset($_COOKIE["loggedin"]) && isset($_COOKIE["password"]) && isset($_COOKIE["username"])) {    
                $this->setUser($_COOKIE["username"], $_COOKIE["password"], false, false);
                $this->setMessage("Welcome back with cookie");
                return true;
            } 
        }
        return false;
    }

    public function setMessage(string $message){
        $_SESSION['message'] = $message;
    }

    public function setUser(string $userName, string $password, bool $keepLoggedIn, bool $inValid){
        $_SESSION['username'] = $userName;
        if (!$inValid) {
            $this->password = $password;
            $this->keepLoggedIn = $keepLoggedIn;
            $_SESSION['loggedin'] = true;
            if ($keepLoggedIn) {
                setcookie("username", $userName, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                setcookie("loggedin", $keepLoggedIn, time() + (86400 * 30), "/");
            }
        }
        else {
            $_SESSION['loggedin'] = false;
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
        return $_SESSION['loggedin'];
    }

    public function getUserName() : string{
        return $_SESSION['username'];
    }

    public function getMessage() : string{
        return $_SESSION['message'];
    }*/

}