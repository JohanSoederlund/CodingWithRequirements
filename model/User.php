<?php

require_once('model/StoreUser.php');


class User {

    private $storeUser;
    private $message = "";
    private $invalidCredentials;
    private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    public function __construct(string $userName, string $password, string $passwordRepeat, bool $tryRegistrate, bool $keepLogedIn) {
        $this->storeUser = new StoreUser();
        if ($tryRegistrate) {
            $this->tryRegistrate($userName, $password, $passwordRepeat);
        } else {
            $this->tryLogin($userName, $password, $keepLogedIn);
        }
    }

    public function getMessage() : string {
        return $this->message;
    }

    public function getInvalidCredentials() : bool {
        return $this->invalidCredentials;
    }

    private function tryRegistrate(string $userName, string $password, string $passwordRepeat){
        if ($this->validateUsernameAndPasswordForRegistration($userName, $password, $passwordRepeat)) {
            if($this->storeUser->registerToDB($userName, $password)){
                $this->invalidCredentials = false;
                $this->message = "Registered new user.";
            } else {
                $this->invalidCredentials = true;
                $this->message = "User exists, pick another username.";
            }
        } 
    }

    private function validateUsernameAndPasswordForRegistration(string $userName, string $password, string $repeatPassword) : bool{
        if (!is_string($userName) || strlen($userName) < 3) {
            $this->message = "Username has too few characters, at least 3 characters. ";
            $this->invalidCredentials = true;
            return false;
        } 
        if (!is_string($password) || strlen($password) < 6) {
            $this->message = "Password has too few characters, at least 6 characters.";
            $this->invalidCredentials = true;
            return false;
        }
        if ($password !== $repeatPassword) {
            $this->message = "Passwords do not match.";
            $this->invalidCredentials = true;
            return false;
        }
        if (strlen($userName) != strlen(preg_replace(self::$whiteListCharacters, "", $userName))) {
            $this->message = "Illegal character(s) in username.";
            $this->invalidCredentials = true;
            return false;
        }
        if (strlen($userName) != strlen(preg_replace(self::$whiteListCharacters, "", $password))) {
            $this->message = "Illegal character(s) in password.";
            $this->invalidCredentials = true;
            return false;
        }
        $this->invalidCredentials = false;
        return true;
    }

    private function tryLogin(string $userName, string $password, bool $keepLogedIn){
        if ($this->validateUsernameAndPasswordForLogin($userName, $password)) {
            
            if ($this->storeUser->matchUserWithDB($userName, $password)) {
                $this->invalidCredentials = false;
                if ($keepLogedIn) {
                    $this->message = "Welcome and you will be remembered";
                } else {
                    $this->message = "Welcome";
                }
            } else {
                $this->invalidCredentials = true;
                $this->message = "Wrong name or password";
            }
        }
    }

    private function validateUsernameAndPasswordForLogin(string $userName, string $password) : bool{
        if (!is_string($userName) || $userName === '') {
            $this->message = "Username is missing";
            $this->invalidCredentials = true;
            return false;
        } 
        if (!is_string($password) || $password === '') {
            $this->message = "Password is missing";
            $this->invalidCredentials = true;
            return false;
        }
        $this->invalidCredentials = false;
        return true;
    }

}