<?php


class Session {

    private $userName;
    private $password;
    private $keepLoggedIn;

    public function __construct($userName, $password, $keepLoggedIn) {
        assert(session_status() != PHP_SESSION_NONE);


    }

    public function getUserName(){
        return $this->userName;
    }

    public function getPassword(){
        return $this->password;
    }

    public function terminate(){
    }

}