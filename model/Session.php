<?php


class Session {

    private $userName;
    private $password;
    private $keepLoggedIn;

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        
        if (!isset($_SESSION['UserName'])) {
            $this->setUser("");
        }
        

    }

    public function setLogedInUser($userName, $password, $keepLoggedIn){
        $this->userName = $userName;
        $this->password = $password;
        $this->keepLoggedIn = $keepLoggedIn;
        $_SESSION['UserName'] = $userName;
        $_SESSION['loggedIn'] = true;
        if ($keepLoggedIn) {
            setcookie("username", $userName, time() + (86400 * 30), "/");
            setcookie("password", $password, time() + (86400 * 30), "/");
            setcookie("logedin", true, time() + (86400 * 30), "/");
            //setcookie("username", $userName, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
            //setcookie("password", $password, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
            //setcookie("logedin", true, time() + (86400 * 30), "/", 'http://johansoederlund.000webhostapp.com', isset($_SERVER["HTTP"]), true);
        }
    }

    public function logInWithCookies(){
        if (isset($_COOKIE["logedin"]) && isset($_COOKIE["password"]) && isset($_COOKIE["username"])) {
            $this->setLogedInUser($_COOKIE["username"], $_COOKIE["password"], false);
        }
    }

    public function isLogedInCookieValid(){
        if (isset($_COOKIE["logedin"]) && isset($_COOKIE["password"]) && isset($_COOKIE["username"])) {
            
            return $_COOKIE["logedin"];
        }
        return false;
    }

    public function setUser($userName){
        $this->userName = $userName;
        $_SESSION['UserName'] = $userName;
    }

    public function getUserName(){
        return $this->userName;
    }

    public function getPassword(){
        return $this->password;
    }

    public function terminate(){
        $this->setLogedInUser("", "", false);
        $_SESSION['loggedIn'] = false;
        setcookie("username", "", time() - 3600, "/");
        setcookie("password", "", time() - 3600, "/");
        setcookie("logedin", false, time() - 3600, "/");
    }

}