<?php


class Session {


    //TODO: STATIC???
    private $userName;
    private $password;
    private $loggedIn;
    private $keepLoggedIn;
    private $message;

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        } 
        if (!isset($_SESSION['UserName'])) {
            $this->setUser("", "", false, true);
        }
        if (!isset($_SESSION['message'])) {
            $this->setMessage("");
        }
    }

    public function getLoggedIn(){
        return $_SESSION['loggedIn'];
    }

    public function getUserName(){
        return $_SESSION['UserName'];
    }

    public function getMessage() : string{
        return $_SESSION['message'];
    }

    public function setMessage(string $message){
        $this->message = $message;
        $_SESSION['message'] = $this->message;
    }

    public function setUser(string $userName, string $password, bool $keepLoggedIn, bool $inValid){
        $this->userName = $userName;
        $_SESSION['UserName'] = $userName;
        if (!$inValid) {
            $this->password = $password;
            $this->keepLoggedIn = $keepLoggedIn;
            $_SESSION['loggedIn'] = true;
            if ($keepLoggedIn) {
                setcookie("username", $userName, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                setcookie("loggedin", $keepLoggedIn, time() + (86400 * 30), "/");
                //setcookie("username", $userName, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
                //setcookie("password", $password, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
                //setcookie("loggedin", true, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
            }
        }
        else {
            $_SESSION['loggedIn'] = false;
        }
    }

    public function isLoggedInCookieValid(){
        if (isset($_COOKIE["loggedin"])) {
            return $_COOKIE["loggedin"];
        }
        return false;
    }

    public function logInWithCookies(){
        if (isset($_COOKIE["loggedin"]) && isset($_COOKIE["password"]) && isset($_COOKIE["username"])) {    
            $this->setUser($_COOKIE["username"], $_COOKIE["password"], false, false);
            $this->setMessage("Welcome back with cookie");
        }
    }

    public function terminate(){
        $this->setMessage("Bye bye!");
        $this->setUser(" ", " ", false, true);

        setcookie("username", "", time() - 3600, "/");
        setcookie("password", "", time() - 3600, "/");
        setcookie("loggedin", false, time() - 3600, "/");
    }

}