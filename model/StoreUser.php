<?php


class StoreUser {

    private $userName = "Admin";
    private $password = "Password";

    public function getUserName(){
        return $this->userName;
    }

    public function getPassword(){
        return $this->password;
    }

}