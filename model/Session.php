<?php


class Session {

    private static $whiteListCharacters = "/[^a-zA-Z0-9]/";
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $secret = 'secret123';

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        //$this->setUser("", "", false, true);
    }

    private function getWhiteListCharacters() : string {
        return self::$whiteListCharacters;
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

    public function setMessage(string $message){
        $_SESSION['message'] = $message;
    }

    public function createSessionFromCookie() : bool {
        if ($this->isSessionLost() && isset($_COOKIE["loggedin"]) && isset($_COOKIE["username"])) {
            if (!$this->isCookieManipulated()) {   
                $this->setUser($_COOKIE["username"], $_COOKIE[self::$cookiePassword], false, false);
                $this->setMessage("Welcome back with cookie");
                return true;
            } else {
                $this->setMessage("Wrong information in cookies");
            }
        }
        return false;
    }

    private function isSessionLost() : bool {
        if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username']) || !$_SESSION['loggedin']) {
            return true;
        } 
        return false;
    }

    private function isCookieManipulated() : bool {
        $passwordPreg = preg_replace($this->getWhiteListCharacters(), "", self::$secret);
        if (isset($_COOKIE[self::$cookiePassword]) && password_verify($passwordPreg, $_COOKIE[self::$cookiePassword])) {
            return false;
        } else {
            return true;
        }
    }

    public function setUser(string $userName, string $cookiePassword, bool $keepLoggedIn, bool $inValid){
        $_SESSION['username'] = $userName;
        if (!$inValid) {
            $_SESSION['loggedin'] = true;
            if ($keepLoggedIn) {
                setcookie("username", $userName, time() + (86400 * 30), "/");
                setcookie("loggedin", $keepLoggedIn, time() + (86400 * 30), "/");
                
                //prepared to change to cookiePassword implementation if nececssary.
                $passwordPreg = preg_replace($this->getWhiteListCharacters(), "", self::$secret);
                $passwordHashed = password_hash($passwordPreg, PASSWORD_DEFAULT);
                setcookie(self::$cookiePassword, $passwordHashed, time() + (86400 * 30), "/");
                //$browserPreg = preg_replace($this->getWhiteListCharacters(), "", $_SERVER['HTTP_USER_AGENT']);
                //$browserHashed = password_hash($browserPreg, PASSWORD_DEFAULT);
                //setcookie("secret", $browserHashed, time() + (86400 * 30), "/");
            }
        }
        else {
            $_SESSION['loggedin'] = false;
        }
    }

    public function terminateSession(){
        $this->setMessage("Bye bye!");
        $this->setUser("", "", false, true);
        setcookie(self::$cookiePassword, "", time() - 3600, "/");
        setcookie("username", "", time() - 3600, "/");
        setcookie("loggedin", false, time() - 3600, "/");
    }



}