<?php

require_once('model/StoreUser.php');


class User {

    private $storeUser;
    private $session;
    private $validCredentials;
    private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    public function __construct(Session $session) {
        $this->storeUser = new StoreUser();
        $this->session = $session;
    }

    public function getvalidCredentials() : bool {
        return $this->validCredentials;
    }

    public function tryRegister(string $userName, string $password, string $passwordRepeat) : bool {
        $this->session->setUserName($userName);
        $this->validCredentials = false;
        if ($this->validateUsernameAndPasswordForRegistration($userName, $password, $passwordRepeat)) {
            if($this->storeUser->registerToDB($userName, $password)){
                $this->session->setMessage("Registered new user.");
                $this->validCredentials = true;
            } else {
                $this->session->setMessage("User exists, pick another username.");
            }
        } 
        return $this->validCredentials;
    }

    private function validateUsernameAndPasswordForRegistration(string $userName, string $password, string $repeatPassword) : bool{
        if (!is_string($userName) || strlen($userName) < 3) {
            $this->session->setMessage("Username has too few characters, at least 3 characters. ");
            return false;
        } 
        if (!is_string($password) || strlen($password) < 6) {
            $this->session->setMessage("Password has too few characters, at least 6 characters.");
            return false;
        }
        if ($password !== $repeatPassword) {
            $this->session->setMessage("Passwords do not match.");
            return false;
        }
        if (strlen($userName) != strlen(preg_replace(self::$whiteListCharacters, "", $userName))) {
            $this->session->setMessage("Illegal character(s) in username.");
            return false;
        }
        if (strlen($password) != strlen(preg_replace(self::$whiteListCharacters, "", $password))) {
            $this->session->setMessage("Illegal character(s) in password.");
            return false;
        }
        return true;
    }

    public function tryLogin(string $userName, string $password) : bool{
        $this->session->setUserName($userName);
        $this->validCredentials = false;
        if ($this->validateUsernameAndPasswordForLogin($userName, $password)) {
            if ($this->storeUser->matchUserWithDB($userName, $password)) {
                $this->validCredentials = true;
                $this->session->setMessage("Welcome");
                $this->session->setLoggedIn($this->validCredentials);
            } else {
                $this->session->setMessage("Wrong name or password");
            }
        }
        return $this->validCredentials;
    }

    private function validateUsernameAndPasswordForLogin(string $userName, string $password) : bool{
        if (!is_string($userName) || $userName === '') {
            $this->session->setMessage("Username is missing");
            return false;
        } 
        if (!is_string($password) || $password === '') {
            $this->session->setMessage("Password is missing");
            return false;
        }
        return true;
    }

}