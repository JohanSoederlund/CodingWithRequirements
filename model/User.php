<?php

require_once('model/StoreUser.php');


class User {

    private $storeUser;
    private $message = "";
    private $invalidCredentials;
    private $tryRegistrate;

    public function __construct(string $userName, string $password, string $passwordRepeat, bool $tryRegistrate, bool $keepLogedIn) {
        $this->storeUser = new StoreUser();
        if ($tryRegistrate) {
            $this->tryRegistrate($userName, $password, $passwordRepeat);
        } else {
            $this->tryLogin($userName, $password, $keepLogedIn);
        }
    }
    private function tryLogin($userName, $password, $keepLogedIn){
        if ($this->validateUsernameAndPasswordForLogin($userName, $password)) {
            
            if ($this->storeUser->matchWithDB($userName, $password)) {
                $this->invalidCredentials = false;
                if ($keepLogedIn) {
                    $this->message = "Welcome and you will be remembered";
                }
                $this->message = "Welcome";
            } else {
                $this->invalidCredentials = true;
                $this->message = "Wrong name or password";
            }
        }
    }

    private function tryRegistrate($userName, $password, $passwordRepeat){
        if ($this->validateUsernameAndPasswordForRegister($userName, $password, $passwordRepeat)) {

            if($this->storeUser->registerToDB($userName, $password)){
                $this->invalidCredentials = false;
                $this->message = "Registered new user.";
            } else {
                $this->invalidCredentials = true;
                $this->message = "User exists, pick another username.";
            }
            
        } 
        
    }


    public function getMessage(){
        return $this->message;
    }

    public function getInvalidCredentials(){
        return $this->invalidCredentials;
    }

    private function validateUsernameAndPasswordForRegister($userName, $password, $repeatPassword){

        //ALSO check for invalid chars
        if (!is_string($userName) || strlen($userName) < 3) {
            $this->message = "Username has too few characters, at least 3 characters.";
            $this->invalidCredentials = true;
            //TODO DONT RETURN
            return false;
        } 
        if (!is_string($password) || strlen($password) < 6) {
            $this->message .= "Password has too few characters, at least 6 characters.";
            $this->invalidCredentials = true;
            return false;
        }
        if ($password !== $repeatPassword) {
            $this->message = "Passwords do not match.";
            $this->invalidCredentials = true;
            return false;
        }
        $this->invalidCredentials = false;
        return true;
    }

    private function validateUsernameAndPasswordForLogin($userName, $password){
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