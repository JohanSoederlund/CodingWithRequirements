<?php


class Session {

    //private static $whiteListCharacters = "/[^a-zA-Z0-9]/";
    //private static $cookiePassword = 'LoginView::CookiePassword';
    //private static $secret = 'secret123';
    //private static $browser = 'browser';

    private static $loggedIn = 'Session::LoggedIn';
	private static $userName = 'Session::UserName';
	private static $message = 'Session::Message';

    public function __construct() {
        assert(session_status() != PHP_SESSION_NONE);
        $this->setMessage("");
    }

    public function getLoggedIn() : bool {
        if(!isset($_SESSION[self::$loggedIn])){
            $_SESSION[self::$loggedIn] = false;
        }
        return $_SESSION[self::$loggedIn];
    }

    public function setLoggedIn(bool $loggedIn) {
        $_SESSION[self::$loggedIn] = $loggedIn;
    }

    public function getUserName() : string {
        if(!isset($_SESSION[self::$userName])){
            $_SESSION[self::$userName] = "";
        }
        return $_SESSION[self::$userName];
    }

    public function setUserName(string $userName) {
        $_SESSION[self::$userName] = $userName;
    }

    public function getMessage() : string {
        if (!isset($_SESSION[self::$message])){
            $_SESSION[self::$message] = "";
        }
        return $_SESSION[self::$message];
    }

    public function setMessage(string $message){
        $_SESSION[self::$message] = $message;
    }

    public function createFromCookie(string $userName) {
        $this->setUserName($userName);
        $this->setLoggedIn(true);
    }

    public function terminate() {
        $this->setMessage("Bye bye!");
        $this->setUserName("");
        $this->setLoggedIn(false);
    }

}