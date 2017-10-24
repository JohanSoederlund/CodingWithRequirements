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
        $messaage = "";
        $valid = true;
        if (!is_string($userName) || strlen($userName) < 3) {
            $messaage .= "Username has too few characters, at least 3 characters. ";
            $valid = false;
        } 
        if (!is_string($password) || strlen($password) < 6) {
            $messaage .= "Password has too few characters, at least 6 characters.";
            $valid = false;
        }
        if (!$valid) {
            $this->session->setMessage($messaage);
            return $valid;
        }
        if ($password !== $repeatPassword) {
            $this->session->setMessage("Passwords do not match.");
            return false;
        }
        if (strlen($userName) != strlen(preg_replace(self::$whiteListCharacters, "", $userName))) {
            //Adding strip_tags call due to automatic test demanding "<a>abc</a>" => "abc", and original algo gives "aabca"
            $userName = strip_tags($userName);
            $this->session->setUserName(preg_replace(self::$whiteListCharacters, "", $userName));
            $this->session->setMessage("Username contains invalid characters.");
            return false;
        }
        if (strlen($password) != strlen(preg_replace(self::$whiteListCharacters, "", $password))) {
            $this->session->setMessage("Password contains invalid characters.");
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
    public function tryLogin(string $userName, string $password, bool $hidden) : bool{
        $this->session->setUserName($userName);
        $this->validCredentials = false;
        if ($this->validateUsernameAndPasswordForLogin($userName, $password)) {
            if ($this->saveUser->matchUserWithDB($userName, $password)) {
                $this->validCredentials = true;
                $this->session->setMessage("Welcome");
                $this->session->setLoggedIn($this->validCredentials);
                $this->setHidden($hidden);
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
    
    /**
	* Save username to shared database if user wants to be visible and sets session variable for visibility
    * @param bool $hidden - representation of user visibility towards other users
	*/
    private function setHidden(bool $hidden) {
        if ($this->session->getLoggedIn() && !$hidden) {
            $this->saveUser->saveToVisibleDB($this->session->getUserName());
        }
        $this->session->setHidden($hidden);
    }

    /**
    * @return array All current usernames strings that is set to be visible and are logged in.
	*/
    public function getVisibleUsers() : array {
        if ($this->session->getLoggedIn() && !$this->session->getHidden()) {
            return $this->saveUser->getVisibleUsersFromDB();
        } 
        throw new Exception("Access to logged in users denied");
    }

    /**
	* Remove this user from visibility file
	*/
    public function deleteToVisibleDB() {
        $this->saveUser->deleteToVisibleDB($this->session->getUserName());
    }
    
}