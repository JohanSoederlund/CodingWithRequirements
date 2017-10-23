<?php

class User {

    private $saveUser;
    private $session;
    private $validCredentials;
    private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    /**
    * Instatiates a model representation of a User
    * Responsible for login and register validation
    * @param Session $session - server-client session
	*/
    public function __construct(Session $session) {
        $this->saveUser = new SaveUser();
        $this->session = $session;
    }

    public function getvalidCredentials() : bool {
        return $this->validCredentials;
    }

    /**
	* Character and string validation, if successful validates against database model
    * @param string $userName - chosen username
    * @param string $password - chosen password
    * @param string $passwordRepeat - to avoid user input error
    * @return bool $validCredentials only true when registration is successful
	*/
    public function tryRegister(string $userName, string $password, string $passwordRepeat) : bool {
        $this->session->setUserName($userName);
        $this->validCredentials = false;
        if ($this->validateUsernameAndPasswordForRegistration($userName, $password, $passwordRepeat)) {
            if($this->saveUser->registerToDB($userName, $password)){
                $this->session->setMessage("Registered new user.");
                $this->validCredentials = true;
            } else {
                $this->session->setMessage("User exists, pick another username.");
            }
        } 
        return $this->validCredentials;
    }

    /**
	* Basic rule-set for strings
	*/
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

    /**
	* Character and string validation, if successful validates against database model
    * @param string $userName - chosen username
    * @param string $password - chosen password
    * @return bool $validCredentials only true when login is successful
	*/
    public function tryLogin(string $userName, string $password) : bool{
        $this->session->setUserName($userName);
        $this->validCredentials = false;
        if ($this->validateUsernameAndPasswordForLogin($userName, $password)) {
            if ($this->saveUser->matchUserWithDB($userName, $password)) {
                $this->validCredentials = true;
                $this->session->setMessage("Welcome");
                $this->session->setLoggedIn($this->validCredentials);
            } else {
                $this->session->setMessage("Wrong name or password");
            }
        }
        return $this->validCredentials;
    }

    /**
	* Basic rule-set for strings
	*/
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